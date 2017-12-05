<?php
namespace framework\web\ui;
use framework\web\UI;

/**
 * Class UIWindow
 * @package framework\web\ui
 *
 * @property string $title
 * @property bool $centered
 */
class UIWindow extends UIContainer
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var bool
     */
    private $centered = false;

    /**
     * UIWindow constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->visible = false;
    }

    /**
     * @return string
     */
    public function uiSchemaClassName(): string
    {
        return 'Window';
    }

    public function uiSchemaEvents(): array
    {
        return [
            'close' => 'hide.bs.modal',
            'hide'  => 'hide.bs.modal',
            'show'  => 'show.bs.modal',
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return bool
     */
    public function isCentered(): bool
    {
        return $this->centered;
    }

    /**
     * @param bool $centered
     */
    public function setCentered(bool $centered)
    {
        $this->centered = $centered;
    }

    public function show()
    {
        parent::show();

        UI::currentRequired()->addWindow($this);
    }

    public function free()
    {
        parent::free();

        UI::currentRequired()->removeWindow($this);
    }

    /**
     * Close window.
     */
    public function close()
    {
        $this->hide();
        $this->free();
    }

    public function provideUserInput(array $data)
    {
        parent::provideUserInput($data);
    }

    public function synchronizeUserInput(array $data)
    {
        if ($data['close']) {
            $this->hide();
        }
    }
}