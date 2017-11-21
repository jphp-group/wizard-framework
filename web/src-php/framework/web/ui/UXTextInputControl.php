<?php
namespace framework\web\ui;

/**
 * Class UXTextInputControl
 * @package framework\web\ui
 *
 * @property string $text
 * @property string $placeholder
 * @property bool $editable
 * @property string $textAlign
 */
abstract class UXTextInputControl extends UXNode
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
        if (isset($data['text'])) {
            $this->text = $data['text'];
        }
    }
}