<?php
namespace framework\web\ui;

/**
 * Class UXLabeled
 * @package framework\web\ui
 */
abstract class UXLabeled extends UXNode
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
        parent::__construct();

        if ($text) {
            $this->setText($text);
        }
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