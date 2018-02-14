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
use framework\web\ui\UIWindow;
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
     * browsers by sessionId.
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

    /**
     * @event addTo
     * @param Event $e
     * @throws \Exception
     */
    protected function handleAddTo(Event $e)
    {
        if ($e->context instanceof DesktopApplication) {
            $this->app = $e->context;

            $this->initializeWebLibs();

            $this->app->on('launch', function () {
                $window = $this->createWindow();
                $window->show();
            });
        } else {
            throw new \Exception("DesktopUI module only for Desktop Applications");
        }
    }

    /**
     * @param string $sessionId
     * @return CefBrowser|null
     */
    protected function findBrowser(string $sessionId): ?CefBrowser
    {
        return $this->cefBrowsers[$sessionId];
    }

    /**
     * @param string $sessionId
     * @return CefBrowserWindow|null
     */
    protected function findBrowserWindow(string $sessionId): ?CefBrowserWindow
    {
        if ($browser = $this->findBrowser($sessionId)) {
            return CefBrowserWindow::find($browser);
        }

        return null;
    }

    protected function createWindow($url = '/', bool $asDialog = false, bool $asModal = false)
    {
        $sessionId = str::uuid();
        $browser = $this->app->getCefClient()->createBrowser("wizard://ui/$sessionId{$url}", true);

        $this->cefBrowsers[$sessionId] = $browser;

        $window = new CefBrowserWindow($browser, $asDialog);

        $window->onClosing(function () use ($window) {
            $window->free();
        });

        $window->size = [100, 100];
        $window->position = [-2000, -2000];
        $window->resizable = true;

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

    protected $windows = [];

    protected function createUi(string $path, string $uiClass, UISocket $socket)
    {
        /** @var AppUI $ui */
        $ui = new $uiClass($socket);

        $ui->on('detectCurrentForm', function (Event $e) use ($ui) {
            $path = $e->data['path'];

            if (str::startsWith($path, '/~')) {
                $window = $this->windows[str::sub($path, 2)];

                if ($window instanceof UIWindow && $ui instanceof AppUI) {
                    $form = $window->data('--form');

                    if ($form) {
                        $ui->setCurrentForm($form);
                        $e->consume();

                        unset($this->windows[$window->uuid]);
                    }
                }
            }
        });

        $ui->on('rendered', function (Event $e) {
            if ($window = $this->findBrowserWindow($e->data['sessionId'])) {
                ['size' => $size] = $e->data;

                if ($size[0] > 0 && $size[1] > 0) {
                    $window->innerSize = $size;
                    $window->centerOnScreen();
                }
            }
        });

        $ui->on('addWindow', function (Event $e) use ($path) {
            /** @var UIWindow $window */
            $window = $e->context;

            if ($window->showType == 'popup') return;

            $this->windows[$window->uuid] = $window;

            $cefWindow = $this->createWindow(
                "{$path}~{$window->uuid}", $window->showType == 'dialog', $window->showType == 'dialog'
            );

            $window->data('--cef-window', $cefWindow);

            if ($window->size[0] > 0 && $window->size[0] > 0) {
                $cefWindow->innerSize = $window->size;
                $cefWindow->centerOnScreen();
            }

            $cefWindow->title = $window->title;
            $cefWindow->resizable = true;

            $cefWindow->show();
            $e->consume();
        });

        $ui->on('removeWindow', function (Event $e) {
            /** @var UIWindow $window */
            $window = $e->context;

            if ($window->showType == 'popup') return;

            $cefWindow = $window->data('--cef-window');

            if ($cefWindow instanceof CefBrowserWindow) {
                $cefWindow->free();
            }
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
            $sessionId = $this->app->stamp;

            if (str::startsWith($request->url, 'wizard://ui/')) {
                $urlArgument = str::sub($request->url, 12);
                [$sessionId, $urlArgument] = str::split($urlArgument, '/', 2);
            }

            $args = [
                'sessionId' => $sessionId,
                'title' => '',
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
            if (!($ui = $this->isolatedSessionInstances[$sessionId][UI::class])) {
                $this->isolatedSessionInstances[$sessionId][UI::class] = $ui = $this->createUi($path, $uiClass, $socket);
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

        $this->app->addResource("/dnext/engine-{$this->app->stamp}.min.css", $this->dnextCssFile ?: 'res://dnext-engine.min.css');
        $this->app->addResource("/dnext/engine-{$this->app->stamp}.js", $this->dnextJsFile ?: 'res://dnext-engine.js');
        $this->app->addResource("/dnext/engine-{$this->app->stamp}.js.map",
            $this->dnextJsFile ? "$this->dnextJsFile.map" : 'res://dnext-engine.js.map'
        );
    }
}