<?php
namespace framework\web\ui\effects;

use framework\core\Component;
use framework\core\Event;
use framework\web\ui\UINode;

/**
 * Class UIEffect
 * @package framework\web\ui\effects
 *
 * @property UINode $owner
 */
abstract class UIEffectComponent extends Component
{
    /**
     * @var string
     */
    private $when = 'render';

    /**
     * @var array
     */
    private $ownerBindIds = [];

    /**
     * @param UINode $node
     */
    private function apply(UINode $node)
    {
        $this->trigger(new Event('apply', $this, $node));
    }

    /**
     * @param UINode $node
     */
    private function reset(UINode $node)
    {
        $this->trigger(new Event('reset', $this, $node));
    }

    /**
     * @event addTo
     * @param Event $e
     */
    public function handleAddTo(Event $e)
    {
        $owner = $e->context;

        if ($owner instanceof UINode) {
            $this->trigger(new Event('initialize', $this, $owner));

            $this->on('change-any', function () use ($owner) {
                $this->apply($owner);
            }, __CLASS__);

            switch ($this->when) {
                case 'render':
                    $this->ownerBindIds['render'] = $owner->bind('render', function () use ($owner) {
                        $this->apply($owner);
                    });

                    if ($owner->isConnectedToUI()) {
                        $this->apply($owner);
                    }

                    break;

                case 'click':
                    $clickCount = 0;
                    $this->ownerBindIds['click'] = $owner->bind('click', function () use ($owner, &$clickCount) {
                        if ($clickCount % 2 == 0) {
                            $this->apply($owner);
                        } else {
                            $this->reset($owner);
                        }

                        $clickCount += 1;
                    });
                    break;

                case 'hover':
                    $this->ownerBindIds['mouseEnter'] = $owner->bind('mouseEnter', function () use ($owner) {
                        $this->apply($owner);
                    });

                    $this->ownerBindIds['mouseLeave'] = $owner->bind('mouseLeave', function () use ($owner) {
                        $this->reset($owner);
                    });

                    break;
            }
        }
    }

    /**
     * @event removeFrom
     * @param Event $e
     */
    public function handleRemoveFrom(Event $e)
    {
        $owner = $e->context;

        $this->off('change-any', __CLASS__);

        if ($owner instanceof UINode) {
            foreach ($this->ownerBindIds as $event => $bindId) {
                $owner->off($event, $bindId);
            }

            $this->ownerBindIds = [];

            $this->trigger(new Event('finalize', $this, $owner));
        }
    }

    /**
     * @return string
     */
    protected function getWhen(): string
    {
        return $this->when;
    }

    /**
     * @param string $when
     */
    protected function setWhen(string $when)
    {
        $this->when = $when;
    }
}