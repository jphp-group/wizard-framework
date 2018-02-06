<?php
namespace framework\web\ui;
use framework\web\UI;

/**
 * Class UIWindow
 * @package framework\web\ui
 *
 * @property string $title
 * @property bool $centered
 * @property bool $closable
 * @property UINode $footer
 * @property bool $resizable
 * @property bool $showType
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
     * @var bool
     */
    private $closable = true;

    /**
     * @var bool
     */
    private $resizable = false;

    /**
     * popup, window, dialog, kiosk
     * @var string
     */
    private $showType = 'popup';

    /**
     * @var UINode
     */
    private $footer;

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
    protected function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    protected function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return bool
     */
    protected function isCentered(): bool
    {
        return $this->centered;
    }

    /**
     * @param bool $centered
     */
    protected function setCentered(bool $centered)
    {
        $this->centered = $centered;
    }

    /**
     * @return bool
     */
    protected function isClosable(): bool
    {
        return $this->closable;
    }

    /**
     * @param bool $closable
     */
    protected function setClosable(bool $closable)
    {
        $this->closable = $closable;
    }

    /**
     * @return UINode|null
     */
    protected function getFooter(): ?UINode
    {
        return $this->footer;
    }

    /**
     * @param UINode|null $footer
     */
    protected function setFooter(?UINode $footer)
    {
        $this->footer = $footer;
    }

    /**
     * @return bool
     */
    protected function isResizable(): bool
    {
        return $this->resizable;
    }

    /**
     * @param bool $resizable
     */
    protected function setResizable(bool $resizable)
    {
        $this->resizable = $resizable;
    }

    /**
     * @return string
     */
    protected function getShowType(): string
    {
        return $this->showType;
    }

    /**
     * @param string $showType
     */
    protected function setShowType(string $showType)
    {
        $this->showType = $showType;
    }

    /**
     * @return array
     */
    public function innerNodes(): array
    {
        $nodes = parent::innerNodes();

        if ($this->footer) {
            $nodes[] = $this->footer;
        }

        return $nodes;
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