<?php
namespace components;

use framework\web\ui\animations\UIAnimationComponent;
use framework\web\ui\UINode;

/**
 * Class HelloWorldComponent
 * @package components
 */
class HelloWorldComponent extends UIAnimationComponent
{
    /**
     * @var int
     */
    private $myProp = 40;

    /**
     * @return mixed
     */
    protected function getMyProp()
    {
        return $this->myProp;
    }

    /**
     * @param mixed $myProp
     */
    protected function setMyProp($myProp)
    {
        $this->myProp = $myProp;
    }


    /**
     * @event $owner.render
     */
    public function handleRender()
    {
        if ($this->owner instanceof UINode) {
            $this->owner->css([
                'box-shadow' => '0 0 5px black'
            ]);
        }
    }

    /**
     * @event animate
     */
    public function handleAnimate()
    {
        if ($this->owner instanceof UINode) {
            $this->owner->animate(['margin-top' => 50], ['duration' => $this->duration]);
        }
    }

    /**
     * @event reverseAnimate
     */
    public function handleReverseAnimate()
    {
        if ($this->owner instanceof UINode) {
            $this->owner->animate(['margin-top' => 0], ['duration' => $this->duration]);
        }
    }
}