<?php
namespace framework\web\ui;

/**
 * Class UXButton
 * @package framework\web\ui
 */
class UXButton extends UXNode
{
    /**
     * @var string
     */
    private $text;

    /**
     * UXButton constructor.
     * @param string|null $text
     */
    public function __construct(string $text = null)
    {
        if ($text) {
            $this->setText($text);
        }
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Button';
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }
}