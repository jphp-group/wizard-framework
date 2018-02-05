<?php
namespace framework\app\desktop;

use framework\app\AbstractWebAppModule;
use framework\app\desktop\scheme\RouteRequest;
use framework\app\desktop\scheme\RouteResponse;
use framework\core\Annotations;
use framework\core\Event;
use framework\web\UI;

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


    public function __construct()
    {
        parent::__construct();

        $this->on('inject', function (Event $event) {
            if ($event->context instanceof DesktopApplication) {
                $this->app = $event->context;

                $this->initializeWebLibs();
            } else {
                throw new \Exception("DesktopUI module only for Desktop Applications");
            }
        });
    }


    /**
     * @param string $uiClass
     * @return $this
     */
    public function addUI(string $uiClass)
    {
        $reflectionClass = $this->uiClasses[$uiClass] = new \ReflectionClass($uiClass);

        $path = Annotations::getOfClass('path', $reflectionClass);

        $this->app->addRoute($path, function (RouteRequest $request, RouteResponse $response) use ($uiClass, $path) {
            /** @var UI $ui */
            $ui = $this->app->getInstance($uiClass);

            UI::setup($ui);

            $args = [
                'sessionId' => $this->app->getStamp(),
                'title'     => '',
                'urlArgument' => '',
                'prefix' => 'wizard://'
            ];

            $body = $ui->makeHtmlView($path, 'window.NX.ChromiumEmbeddedAppDispatcher', $this->dnextResources, $args);

            $response->contentType = 'text/html';
            $response->body = $body;

            UI::setup(null);
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
        $this->app->addResource('/dnext/material-icons/material-icons.css', 'res://lib/material-icons/material-icons.css');

        $this->app->addResource("/dnext/engine-{$this->app->getStamp()}.min.css", $this->dnextCssFile ?: 'res://dnext-engine.min.css');
        $this->app->addResource("/dnext/engine-{$this->app->getStamp()}.js", $this->dnextJsFile ?: 'res://dnext-engine.js');
        $this->app->addResource("/dnext/engine-{$this->app->getStamp()}.js.map",
            $this->dnextJsFile ? "$this->dnextJsFile.map": 'res://dnext-engine.js.map'
        );
    }
}