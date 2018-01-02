<?php
namespace framework\web\ui;

/**
 * @package framework\web\ui
 *
 * @property string $text
 * @property string $placeholder
 * @property bool $editable
 * @property string $textAlign
 * @property UIFont $font
 */
abstract class UITextInputControl extends UINode
{
    /**
     * @var string
     */
    private $placeholder = '';

    /**
     * @var bool
     */
    private $editable = true;

    /**
     * @var string
     */
    private $textAlign = 'left';

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var UIFont
     */
    private $font;

    public function __construct()
    {
        parent::__construct();

        $this->font = new UIFont();
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
     * @return string
     */
    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder(string $placeholder)
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->editable;
    }

    /**
     * @param bool $editable
     */
    public function setEditable(bool $editable)
    {
        $this->editable = $editable;
    }

    /**
     * @return string
     */
    public function getTextAlign(): string
    {
        return $this->textAlign;
    }

    /**
     * @param string $textAlign
     */
    public function setTextAlign(string $textAlign)
    {
        $this->textAlign = $textAlign;
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
     * @param array $data
     */
    public function provideUserInput(array $data)
    {
        parent::provideUserInput($data);

        if (isset($data['text'])) {
            $this->text = $data['text'];
        }
    }

    public function synchronizeUserInput(array $data)
    {
        parent::synchronizeUserInput($data);

        if (isset($data['text'])) {
            $this->changeRemoteProperty('text', $data['text']);
        }
    }
}