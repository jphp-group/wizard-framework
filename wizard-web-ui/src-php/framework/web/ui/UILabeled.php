<?php
namespace framework\web\ui;

/**
 * @package framework\web\ui
 *
 * @property string $text
 * @property string $textType
 * @property bool $textPreFormatted
 * @property UINode $graphic
 * @property UIFont $font
 * @property array $align
 * @property string $horAlign
 * @property string $verAlign
 */
abstract class UILabeled extends UINode
{
    /**
     * @var string
     */
    private $text = '';

    /**
     * text or html
     * @var string
     */
    private $textType = 'text';

    /**
     * @var bool
     */
    private $textPreFormatted = false;

    /**
     * @var UIFont
     */
    private $font;

    /**
     * @var UINode|null
     */
    private $graphic = null;

    /**
     * @var array
     */
    private $align = ['center', 'center'];

    /**
     * @var string
     */
    private $horAlign = 'center';

    /**
     * @var string
     */
    private $verAlign = 'center';

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
     * @return string
     */
    protected function getTextType(): string
    {
        return $this->textType;
    }

    /**
     * @param string $textType
     */
    protected function setTextType(string $textType)
    {
        $this->textType = $textType;
    }

    /**
     * @return bool
     */
    protected function isTextPreFormatted(): bool
    {
        return $this->textPreFormatted;
    }

    /**
     * @param bool $textPreFormatted
     */
    protected function setTextPreFormatted(bool $textPreFormatted)
    {
        $this->textPreFormatted = $textPreFormatted;
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
    protected function getAlign(): array
    {
        return [$this->verAlign, $this->horAlign];
    }

    /**
     * @param array $align
     */
    protected function setAlign(array $align)
    {
        $this->__set('verAlign', $align[0]);
        $this->__set('horAlign', $align[1]);
    }

    /**
     * @return string
     */
    protected function getHorAlign(): string
    {
        return $this->horAlign;
    }

    /**
     * @param string $horAlign
     */
    protected function setHorAlign(string $horAlign)
    {
        $this->horAlign = $horAlign;
    }

    /**
     * @return string
     */
    protected function getVerAlign(): string
    {
        return $this->verAlign;
    }

    /**
     * @param string $verAlign
     */
    protected function setVerAlign(string $verAlign)
    {
        $this->verAlign = $verAlign;
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