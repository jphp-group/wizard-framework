<?php
namespace components;

use framework\web\ui\animations\UIAnimationComponent;
use framework\web\ui\UINode;

class HelloWorldComponent extends UIAnimationComponent
{
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