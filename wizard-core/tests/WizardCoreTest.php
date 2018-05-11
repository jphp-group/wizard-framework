<?php

use framework\core\Component;
use tester\Assert;
use tester\TestCase;

class SimpleComponent extends Component
{
}

class WizardCoreTest extends TestCase
{
    public function testBasic()
    {
        $component = new SimpleComponent();
        $component->id = "foobar";

        Assert::isEqual("foobar", $component->id, "check component.id");
    }
}