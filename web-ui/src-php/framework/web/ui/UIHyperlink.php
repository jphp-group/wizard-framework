<?php
namespace framework\web\ui;

/**
 * @package framework\web\ui
 *
 * @property string $href
 * @property string $target
 */
class UIHyperlink extends UILabeled
{
    /**
     * @var string
     */
    private $href = '';

    /**
     * @var string
     */
    private $target = '_self';

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Hyperlink';
    }

    /**
     * @return string
     */
    protected function getHref(): string
    {
        return $this->href;
    }

    /**
     * @param string $href
     */
    protected function setHref(string $href)
    {
        $this->href = $href;
    }

    /**
     * @return string
     */
    protected function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    protected function setTarget(string $target)
    {
        $this->target = $target;
    }
}