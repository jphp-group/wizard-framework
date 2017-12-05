<?php
namespace framework\web;

use framework\core\AnnotationEventBinder;
use framework\core\Annotations;
use framework\core\Component;
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
     * UIForm constructor.
     */
    public function __construct()
    {
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
        $this->window->centered = true;
        $this->window->clear();
        $this->window->add($this->layout);
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
        return reflect::typeModule(reflect::typeOf($this))->getName() . ".frm";
    }

    protected function loadFrm(string $frmPath)
    {
        $uiLoader = new UILoader();

        try {
            $data = $uiLoader->loadFromStream($stream = Stream::of($frmPath), 'view');

            $this->setTitle($data['title']);

            $this->layout = $uiLoader->getNode();
            $this->nodesById = $uiLoader->getNodesById();
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