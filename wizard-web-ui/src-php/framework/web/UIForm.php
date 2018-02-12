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
 * @property int $x
 * @property int $y
 * @property array $position
 * @property int $width
 * @property int $height
 * @property array $size
 * @property string $showType
 */
abstract class UIForm extends Component
{
    /**
     * @var array
     */
    private $router = [];

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
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * popup, window
     * @var string
     */
    private $showType = 'popup';

    /**
     * @var EventSignal
     */
    public $onNavigate;

    /**
     * @var EventSignal
     */
    public $onShow;

    /**
     * @var bool
     */
    private $initialized;

    /**
     * UIForm constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->initialized = true;

        $this->loadFrm($this->getFrmPath());
        $this->loadBinds();
    }

    protected function loadBinds()
    {
        if ($this->initialized) {
            parent::loadBinds();
        }
    }

    protected function loadDescription()
    {
        // nop.
    }

    /**
     * @return UIWindow
     */
    protected function fetchWindow(): UIWindow
    {
        if ($this->window) {
            return $this->window;
        }

        return $this->window = $this->createWindow();
    }

    /**
     * @return UIWindow
     */
    protected function createWindow(): UIWindow
    {
        $window = new UIWindow();
        $window->data('--form', $this);

        $window->x = $this->x;
        $window->y = $this->y;
        $window->width  = $this->width;
        $window->height = $this->height;
        $window->title  = $this->title;
        $window->centered = $this->centered;
        $window->closable = $this->closable;
        $window->showType = $this->showType;

        $window->clear();
        $window->add($this->layout);
        $window->footer = $this->footer;

        return $window;
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

        if (UI::current() instanceof AppUI) {
            UI::current()->navigateTo($this, $args);
            return;
        }

        Logger::error('Failed to open the {0} form, it is not registered in App UI', reflect::typeOf($this));
    }

    /**
     * Show window.
     */
    public function show()
    {
        if ($this->showType == 'page') {
            $this->open();
        } else {
            $window = $this->fetchWindow();
            $window->show();
        }
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
        if (isset($this->router['path'])) {
            return [$this->router['path']];
        }

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

            foreach (['title', 'width', 'height', 'x', 'y', 'size', 'position', 'closable',
                         'centered', 'showType', 'router'] as $prop) {
                if (isset($data[$prop])) {
                    $this->{$prop} = $data[$prop];
                }
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
     * @return int
     */
    protected function getX(): int
    {
        return $this->window ? $this->window->x : $this->x;
    }

    /**
     * @param int $x
     */
    protected function setX(int $x)
    {
        $this->x = $x;

        if ($this->window) {
            $this->window->x = $x;
        }
    }

    /**
     * @return int
     */
    protected function getY(): int
    {
        return $this->window ? $this->window->y : $this->y;
    }

    /**
     * @param int $y
     */
    protected function setY(int $y)
    {
        $this->y = $y;

        if ($this->window) {
            $this->window->y = $y;
        }
    }

    /**
     * @return array
     */
    protected function getPosition(): array
    {
        return [$this->x, $this->y];
    }

    /**
     * @param array $value
     */
    protected function setPosition(array $value)
    {
        [$this->x, $this->y] = $value;
    }

    /**
     * @return int
     */
    protected function getWidth(): int
    {
        return $this->window ? $this->window->width : $this->width;
    }

    /**
     * @param int $width
     */
    protected function setWidth(int $width)
    {
        $this->width = $width;

        if ($this->window) {
            $this->window->width = $width;
        }
    }

    /**
     * @return int
     */
    protected function getHeight(): int
    {
        return $this->window ? $this->window->height : $this->height;
    }

    /**
     * @param int $height
     */
    protected function setHeight(int $height)
    {
        $this->height = $height;

        if ($this->window) {
            $this->window->height = $height;
        }
    }

    /**
     * @return array
     */
    protected function getSize(): array
    {
        return [$this->width, $this->height];
    }

    /**
     * @param array $value
     */
    protected function setSize(array $value)
    {
        [$this->width, $this->height] = $value;
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
     * @param string $name
     * @return bool|mixed
     */
    public function __get(string $name)
    {
        if ($node = $this->nodesById[$name]) {
            return $node;
        }

        if (isset($this->components->{$name})) {
            return $this->components->{$name};
        }

        return parent::__get($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        if (isset($this->nodesById[$name])) {
            return true;
        }

        if (isset($this->components->{$name})) {
            return true;
        }

        return false;
    }
}