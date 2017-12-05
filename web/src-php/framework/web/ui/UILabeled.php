<?php
namespace framework\web\ui;

/**
 * @package framework\web\ui
 *
 * @property string $text
 * @property UINode $graphic
 *
 */
abstract class UILabeled extends UINode
{
    /**
     * @var string
     */
    private $text = '';

    /**
     * @var UINode|null
     */
    private $graphic = null;

    /**
     * UXButton constructor.
     * @param string|null $text
     * @param UINode|null $graphic
     */
    public function __construct(string $text = '', UINode $graphic = null)
    {
        parent::__construct();

        if ($text) {
            $this->setText($text);
        }

        if ($graphic) {
            $this->setGraphic($graphic);
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

    /**
     * @return UINode
     */
    public function getGraphic(): ?UINode
    {
        return $this->graphic;
    }

    /**
     * @param UINode $graphic
     */
    public function setGraphic(?UINode $graphic)
    {
        $this->graphic = $graphic;
    }
}