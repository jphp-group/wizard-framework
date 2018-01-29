<?php
namespace framework\web\ui;

use framework\core\EventSignal;

/**
 * Class UIListView
 * @package framework\web\ui
 *
 * @property UINode|null $selected
 * @property int $selectedIndex
 */
class UIListView extends UIContainer
{
    /**
     * @var UINode
     */
    private $selected = null;

    /**
     * @var int
     */
    private $selectedIndex = -1;

    /**
     * @var EventSignal
     */
    public $onChange;

    /**
     * @var EventSignal
     */
    public $onAction;

    /**
     * UIListView constructor.
     * @param array $children
     */
    public function __construct(array $children = [])
    {
        parent::__construct($children);
    }

    public function uiSchemaClassName(): string
    {
        return 'ListView';
    }

    public function uiSchema(): array
    {
        $schema = parent::uiSchema();

        unset($schema['selected']);

        return $schema;
    }


    /**
     * @return UINode
     */
    protected function getSelected(): ?UINode
    {
        return $this->selected;
    }

    /**
     * @param UINode $selected
     */
    protected function setSelected(?UINode $selected)
    {
        $this->selected = $selected;
    }

    /**
     * @return int
     */
    protected function getSelectedIndex(): int
    {
        return $this->selectedIndex;
    }

    /**
     * @param int $selectedIndex
     */
    protected function setSelectedIndex(int $selectedIndex)
    {
        $this->selectedIndex = $selectedIndex;
    }

    public function provideUserInput(array $data)
    {
        parent::provideUserInput($data);

        if (isset($data['selected'])) {
            $this->setSelected($data['selected']);
        }

        if (isset($data['selectedIndex'])) {
            $this->selectedIndex = (int) $data['selectedIndex'];
        }
    }

    public function synchronizeUserInput(array $data)
    {
        parent::synchronizeUserInput($data);

        if (isset($data['selectedIndex'])) {
            $this->changeRemoteProperty('selectedIndex', $data['selectedIndex']);
        }
    }
}