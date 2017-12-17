<?php
namespace framework\web\ui;

/**
 * @package framework\web\ui
 *
 * @property string $text
 * @property UINode $graphic
 * @property UIFont $font
 */
abstract class UILabeled extends UINode
{
    /**
     * @var string
     */
    private $text = '';

    /**
     * @var UIFont
     */
    private $font;

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

        $this->font = new UIFont();

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
    protected function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    protected function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return UINode
     */
    protected function getGraphic(): ?UINode
    {
        return $this->graphic;
    }

    /**
     * @param UINode $graphic
     */
    protected function setGraphic(?UINode $graphic)
    {
        $this->graphic = $graphic;
    }

    /**
     * @return UIFont
     */
    protected function getFont(): UIFont
    {
        return UIFont::wrapper($this, 'font', $this->font);
    }

    /**
     * @param UIFont|array|string $font
     * @throws \TypeError
     */
    protected function setFont($font)
    {
        $this->font = UIFont::fetch($font);
    }

    /**
     * @return array
     */
    public function innerNodes(): array
    {
        $nodes = parent::innerNodes();

        if ($this->graphic) {
            $nodes[] = $this->graphic;
        }

        return $nodes;
    }
}