<?php
namespace framework\app\desktop;

use cef\CefBrowser;
use cef\CefBrowserWindow;
use framework\app\AbstractWebAppModule;
use framework\app\desktop\scheme\RouteRequest;
use framework\app\desktop\scheme\RouteResponse;
use framework\core\Annotations;
use framework\core\Event;
use framework\core\Logger;
use framework\web\AppUI;
use framework\web\SocketMessage;
use framework\web\UI;
use framework\web\UIForm;
use framework\web\UISocket;
use php\lib\str;
use php\time\Timer;
use php\util\SharedMap;

/**
 * Class WebDesktopAppModule
 * @package framework\app\desktop
 */
class WebDesktopAppModule extends AbstractWebAppModule
{
    /**
     * @var DesktopApplication
     */
    private $app;

    /**
     * @var CefBrowserWindow[]
     */
    private $modalWindows = [];

    /**
     * @var CefBrowser[]
     */
    private $cefBrowsers = [];

    /**
     * @var CefBrowserWindow[]
     */
    private $cefBrowserWindows = [];


    /**
     * @var array
     */
    private $isolatedSessionInstances = [];

    public function __construct()
    {
        parent::__construct();

        $this->on('inject', function (Event $event) {
            if ($event->context instanceof DesktopApplication) {
                $this->app = $event->context;

                $this->initializeWebLibs();

                $this->app->on('launch', function () {
                    $window = $this->createWindow('');
                    $window->centerOnScreen();
                    $window->show();
                });
            } else {
                throw new \Exception("DesktopUI module only for Desktop Applications");
            }
        });

    }

    protected function createWindow($url = '/', bool $asDialog = false, bool $asModal = false)
    {
        $browser = $this->app->getCefClient()->createBrowser('wizard://ui' . $url, true);

        $window = new CefBrowserWindow($browser, $asDialog);
        $window->onClosing(function () use ($window) {
            $window->free();
        });

        $window->size = [800, 600];
        $window->centerOnScreen();

        $modalOffset = -1;

        if ($asModal) {
            $modalOffset = sizeof($this->modalWindows);
            $this->modalWindows[] = $window;

            $window->onClosing(function () use ($window, $modalOffset) {
                $this->modalWindows[$modalOffset] = null;
            });
        }

        $window->onActivated(function (CefBrowserWindow $window) use ($modalOffset) {
            Timer::after(150, function () use ($window, $modalOffset) {
                foreach ($this->modalWindows as $i => $win) {
                    if ($win && $i > $modalOffset) {
                        $win->toFront();
                    }
                }
            });
        });

        $this->cefBrowserWindows[] = $window;

        return $window;
    }

    /** @var SharedMap */
    private $formsToShow;

    protected function createUi(string $path, string $uiClass, UISocket $socket)
    {
        if (!$this->formsToShow) {
            $this->formsToShow = new SharedMap([]);
        }

        /** @var AppUI $ui */
        $ui = new $uiClass($socket);
        $ui->on('detectCurrentForm', function (Event $e) use ($ui) {
            /*$path = $e->data['path'];

            if (str::startsWith($path, '/~')) {
                $form = $this->formsToShow->get(str::sub($path, 2));

                if ($form && $ui instanceof AppUI) {
                    $ui->setCurrentForm($form);
                    $e->consume();

                    //$this->formsToShow->remove(spl_object_hash($form));
                }
            }*/
        });

        $ui->on('show', function (Event $e) use ($path, &$formsToShow) {
            /** @var UIForm $form */
            $form = $e->context;

            /*$this->formsToShow->set(spl_object_hash($form), $form);

            $window = $this->createWindow($path . "~" . spl_object_hash($form), true, true);
            $window->size = [300, 100];
            $window->centerOnScreen();
            $window->show();

            $e->consume();*/
        });

        return $ui;
    }

    /**
     * @param string $uiClass
     * @return $this
     */
    public function addUI(string $uiClass)
    {
        $reflectionClass = $this->uiClasses[$uiClass] = new \ReflectionClass($uiClass);

        $path = Annotations::getOfClass('path', $reflectionClass);

        $this->app->addRoute("/ui" . $path . '**', function (RouteRequest $request, RouteResponse $response) use ($uiClass, $path) {
            /** @var UI $ui */
            $ui = $this->app->getInstance($uiClass);

            Logger::info("UI ('{0}', url = '/ui{1}') send to browser", $uiClass, $path);

            UI::setup($ui);

            $urlArgument = '';
            if (str::startsWith($request->url, 'wizard://ui/')) {
                $urlArgument = str::sub($request->url, 12);
            }

            $args = [
                'sessionId' => $this->app->getStamp(),
                'title'     => '',
                'urlArgument' => $urlArgument,
                'prefix' => 'wizard://',
                'uiSocketUrl' => $path
            ];

            $body = $ui->makeHtmlView($path, 'window.NX.ChromiumEmbeddedAppDispatcher', $this->dnextResources, $args);

            $response->contentType = 'text/html';
            $response->body = $body;

            UI::setup(null);
        });

        $this->app->addMessageRouter($path, function (CefBrowser $browser, array $message) use ($uiClass, $path) {
            $type = $message['type'];
            $sessionId = $message['sessionId'] . '_' . $message['sessionIdUuid'];

            if (!($socket = $this->isolatedSessionInstances[$sessionId][UISocket::class])) {
                $this->isolatedSessionInstances[$sessionId][UISocket::class] = $socket = new UISocket();
            }

            /** @var UI $ui */
            if (!($ui = $this->isolatedSessionInstances[$sessionId][$uiClass])) {
                $this->isolatedSessionInstances[$sessionId][$uiClass] = $ui = $this->createUi($path, $uiClass, $socket);
            }

            $ui->linkSocket($socket);

            Logger::trace("New UI socket message, (type = {0}, sessionId = {1})", $type, $sessionId);

            switch ($type) {
                case 'initialize':
                    $socket->initialize($uiClass, new DesktopBrowserUISession($browser), $message);
                    break;

                case 'activate':
                    $socket->activate($uiClass, $message);
                    break;

                default:
                    try {
                        $socket->receiveMessage($uiClass, new SocketMessage($message));
                    } catch (\Throwable $e) {
                        $errId = str::uuid();

                        Logger::error("{0}, {1}", $e->getMessage(), $errId);
                        Logger::error("\n{0}\n\t-> at {1} on line {2}", $e->getTraceAsString(), $e->getFile(), $e->getLine());
                    } finally {
                        UI::setup(null);
                    }

                    break;
            }
        });
    }

    private function initializeWebLibs()
    {
        // bootstrap
        $this->app->addResource('/dnext/bootstrap4/bootstrap.min.css', 'res://lib/bootstrap4/bootstrap.min.css');
        $this->app->addResource('/dnext/bootstrap4/bootstrap.min.js', 'res://lib/bootstrap4/bootstrap.min.js');
        $this->app->addResource('/dnext/bootstrap4/popper.min.js', 'res://lib/bootstrap4/popper.min.js');

        // jquery
        $this->app->addResource('/dnext/jquery/jquery-3.2.1.min.js', 'res://lib/jquery/jquery-3.2.1.min.js');

        // material icons
        foreach (['material-icons.css',
                     'MaterialIcons-Regular.eot',
                     'MaterialIcons-Regular.ijmap',
                     'MaterialIcons-Regular.svg',
                     'MaterialIcons-Regular.ttf',
                     'MaterialIcons-Regular.woff',
                     'MaterialIcons-Regular.woff2',
                 ] as $file) {
            $this->app->addResource("/dnext/material-icons/$file", "res://lib/material-icons/$file");
        }

        $this->app->addResource("/dnext/engine-{$this->app->getStamp()}.min.css", $this->dnextCssFile ?: 'res://dnext-engine.min.css');
        $this->app->addResource("/dnext/engine-{$this->app->getStamp()}.js", $this->dnextJsFile ?: 'res://dnext-engine.js');
        $this->app->addResource("/dnext/engine-{$this->app->getStamp()}.js.map",
            $this->dnextJsFile ? "$this->dnextJsFile.map": 'res://dnext-engine.js.map'
        );
    }
}