<?php
namespace framework\web\ui;

use framework\core\Event;
use framework\web\UIForm;

/**
 * Class UIAlert
 * @package ui
 *
 * @property string $text
 * @property string $textAlign
 * @property array $buttons
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

    private static $kinds = [
        'info' => ['info', 'text-info'],
        'error' => ['error', 'text-danger'],
        'warning' => ['warning', 'text-warning'],
        'success' => ['done', 'text-success'],
        'confirm' => ['help', 'text-primary'],
    ];

    /**
     * UIAlert constructor.
     * @param string $type
     */
    public function __construct(string $type = '')
    {
        parent::__construct();

        if ($type) {
            $this->setType($type);
        }
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