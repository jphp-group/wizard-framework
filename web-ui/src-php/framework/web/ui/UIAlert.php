<?php
namespace framework\web\ui;

use framework\core\Event;
use framework\web\UIForm;

/**
 * Class UIAlert
 * @package ui
 *
 * @property string $text
 * @property string $textType
 * @property string $textAlign
 * @property array $buttons
 * @property bool $preFormatted
 */
class UIAlert extends UIForm
{
    /**
     * info, success, warning, error, confirm
     * @var string
     */
    private $type = '';

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var string
     */
    private $textAlign = 'left';

    /**
     * @var array
     */
    private $buttons = ['ok' => 'OK'];

    /**
     * @var bool
     */
    private $preFormatted = false;

    /**
     * @var array
     */
    private static $kinds = [
        'info' => ['info', 'text-info'],
        'information' => ['info', 'text-info'],
        'error' => ['error', 'text-danger'],
        'warning' => ['warning', 'text-warning'],
        'success' => ['done', 'text-success'],
        'confirm' => ['help', 'text-primary'],
    ];

    /**
     * UIAlert constructor.
     * @param string $type
     * @param array $buttons
     */
    public function __construct(string $type = '', array $buttons = ['ok' => 'OK'])
    {
        parent::__construct();

        if ($type) {
            $this->setType($type);
        }

        if ($buttons) {
            $this->setButtons($buttons);
        }
    }

    /**
     * @return bool
     */
    protected function isPreFormatted(): bool
    {
        return $this->preFormatted;
    }

    /**
     * @param bool $preFormatted
     */
    protected function setPreFormatted(bool $preFormatted)
    {
        $this->preFormatted = $preFormatted;
        $this->{'label'}->textPreFormatted = $preFormatted;
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

        $kind = static::$kinds[$type];

        if ($kind) {
            $this->icon->kind = $kind[0];
            $this->icon->classes = [$kind[1]];
            $this->icon->show();
        } else {
            $this->icon->hide();
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
        $this->label->text = $text;
    }

    /**
     * @return string
     */
    protected function getTextAlign(): string
    {
        return $this->textAlign;
    }

    /**
     * @param string $textAlign
     */
    protected function setTextAlign(string $textAlign)
    {
        $this->textAlign = $textAlign;
        $this->textBox->horAlign = $textAlign;
    }

    /**
     * @return array
     */
    protected function getButtons(): array
    {
        return $this->buttons;
    }

    /**
     * @return string
     */
    protected function getTextType(): string
    {
        return $this->{'label'}->textType;
    }

    /**
     * @param string $textType
     */
    protected function setTextType(string $textType)
    {
        $this->{'label'}->textType = $textType;
    }

    /**
     * @param array $buttons
     */
    protected function setButtons(array $buttons)
    {
        $this->buttons = $buttons;
        $this->buttonBox->clear();

        foreach ($buttons as $id => $button) {
            $uiBtn = new UIButton($button);
            $uiBtn->id = "{$id}Button";
            $this->buttonBox->add($uiBtn);

            $uiBtn->on('click', function () use ($uiBtn, $id) {
                $this->trigger(new Event('action', $uiBtn, $this, ['id' => $id]));
                $this->trigger(new Event("action-$id", $uiBtn, $this, ['id' => $id]));
                $this->hide();
            });
        }

        if ($uiBtn) {
            $uiBtn->kind = 'primary';
        }
    }
}