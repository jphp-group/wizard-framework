<?php

namespace framework\web;

use framework\core\AnnotationEventBinder;
use framework\core\Annotations;
use framework\core\Component;
use framework\core\EventSignal;
use framework\core\Logger;
use framework\web\ui\UIContainer;
use framework\web\ui\UINode;
use framework\web\ui\UIWindow;
use php\io\Stream;
use php\lib\arr;
use php\lib\reflect;
use php\lib\str;

/**
 * Class UIForm
 * @package framework\web
 *
 * @scope session
 *
 * @property UIContainer|UINode $layout
 * @property AppUI $appUI
 * @property string $title
 * @property bool $closable
 * @property bool $centered
 *
 */
abstract class UIForm extends Component
{
    /**
     * @var string
     */
    private $title = '';

    /**
     * @var UINode
     */
    private $layout;

    /**
     * @var UINode
     */
    private $nodesById;

    /**
     * @var UI
     */
    private $connectedUi;

    /**
     * @var UIWindow
     */
    private $window;

    /**
     * @var UINode
     */
    private $footer;

    /**
     * @var bool
     */
    private $closable = true;

    /**
     * @var bool
     */
    private $centered = true;

    /**
     * @var EventSignal
     */
    public $onNavigate;

    /**
     * @var EventSignal
     */
    public $onShow;

    /**
     * UIForm constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->window = new UIWindow();

        $this->loadFrm($this->getFrmPath());
        $this->loadBinds();
    }

    /**
     * Open Form in App UI.
     * @param array $args
     */
    public function open(array $args = [])
    {
        if ($this->appUI) {
            $this->appUI->navigateTo($this, $args);
            return;
        }

        Logger::error('Failed to open the {0} form, it is not registered in App UI', reflect::typeOf($this));
    }

    /**
     * Show window.
     */
    public function show()
    {
        $this->window->connectToUI($this->appUI);

        $this->window->title = $this->title;
        $this->window->centered = $this->centered;
        $this->window->closable = $this->closable;

        $this->window->clear();
        $this->window->add($this->layout);
        $this->window->footer = $this->footer;
        $this->window->show();
    }

    /**
     * Hide Window.
     */
    public function hide()
    {
        //$this->window->connectToUI($this->appUI);
        $this->window->hide();
    }

    /**
     * @return string
     */
    public function getRoutePath(): string
    {
        return arr::first($this->getRoutePaths());
    }

    /**
     * @return array
     */
    public function getRoutePaths(): array
    {
        $path = Annotations::getOfClass('path', new \ReflectionClass($this));

        if ($path) {
            return is_array($path) ? $path : [$path];
        }

        return ["/" . str::replace((new \ReflectionClass($this))->getName(), '\\', '.')];
    }

    protected function getFrmPath()
    {
        switch ($this->getFrmFormat()) {
            case "yml":
            case "yaml":
                return reflect::typeModule(reflect::typeOf($this))->getName() . ".yml";
            default:
                return reflect::typeModule(reflect::typeOf($this))->getName() . ".frm";
        }
    }

    protected function getFrmFormat()
    {
        return 'json';
    }

    protected function loadFrm(string $frmPath)
    {
        $uiLoader = new UILoader();

        try {
            $data = $uiLoader->loadFromStream($stream = Stream::of($frmPath), 'layout', $this->getFrmFormat());

            $this->setTitle($data['title']);

            if (isset($data['closable'])) {
                $this->setClosable($data['closable']);
            }

            if (isset($data['centered'])) {
                $this->setCentered($data['centered']);
            }

            $this->layout = $uiLoader->getNode();
            $this->nodesById = $uiLoader->getNodesById();

            if ($data['footer']) {
                $footerUiLoader = new UILoader();
                $footerUiLoader->load($data['footer']);
                $this->footer = $footerUiLoader->getNode();
                $this->nodesById = flow($uiLoader->getNodesById())
                    ->append($footerUiLoader->getNodesById())
                    ->withKeys()
                    ->toArray();
            }

            $this->layout->connectToUI($this->connectedUi);
        } finally {
            if ($stream) {
                $stream->close();
            }
        }
    }

    protected function loadBinds()
    {
        $binder = new AnnotationEventBinder($this, $this);
        $binder->loadBinds();
    }

    /**
     * @return UI|null
     */
    public function getConnectedUI(): ?UI
    {
        return $this->connectedUi;
    }

    /**
     * @return bool
     */
    public function isConnectedToUI(): bool
    {
        return !!$this->connectedUi;
    }

    /**
     * @param UI $ui
     */
    public function connectToUI(UI $ui)
    {
        $this->connectedUi = $ui;

        if ($this->layout) {
            $this->layout->connectToUI($ui);
        }
    }

    /**
     *
     */
    public function disconnectUI()
    {
        $this->connectedUi = null;
    }

    /**
     * @return AppUI|null
     */
    protected function getAppUI(): ?AppUI
    {
        return $this->connectedUi;
    }

    /**
     * @return UINode|null
     */
    public function getLayout(): ?UINode
    {
        return $this->layout;
    }

    /**
     * @return string
     */
    protected function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    protected function isClosable(): bool
    {
        return $this->closable;
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
     * @param bool $closable
     */
    protected function setClosable(bool $closable)
    {
        $this->closable = $closable;
    }

    /**
     * @param string $title
     */
    protected function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $name
     * @return bool|mixed
     */
    public function __get(string $name)
    {
        if ($node = $this->nodesById[$name]) {
            return $node;
        }

        return parent::__get($name);
    }
}