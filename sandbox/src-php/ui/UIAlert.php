<?php
namespace ui;

use framework\web\UIForm;

/**
 * Class UIAlert
 * @package ui
 *
 * @property string $text
 */
class UIAlert extends UIForm
{
    /**
     * @var string
     */
    private $type = '';

    /**
     * @var string
     */
    private $text = '';

    /**
     * UIAlert constructor.
     * @param string $type
     */
    public function __construct(string $type = '')
    {
        parent::__construct();

        $this->type = $type;
    }

    /**
     * @event okButton.click
     */
    public function doOkButtonAction()
    {
        $this->hide();
    }

    /**
     * @return string
     */
    protected function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    protected function setType(string $type)
    {
        $this->type = $type;
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
        $this->label->text = $text;
    }
}