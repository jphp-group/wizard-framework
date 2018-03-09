<?php

use bundle\aceeditor\AceEditorModule;
use bundle\slider\SliderModule;
use framework\app\web\WebServerAppModule;
use framework\core\Event;
use framework\core\Promise;
use framework\ide\IdeComponentLoader;
use framework\localization\Localizer;
use framework\web\HotDeployer;
use framework\web\WebApplication;
use framework\web\WebAssets;
use framework\web\WebDevModule;
use ui\MainUI;
use wizard\httpclient\HttpClient;
use wizard\httpclient\HttpMonitor;
use wizard\httpclient\HttpResponse;


$monitor = new HttpMonitor();
$monitor->observables = ['http://develnext.org', 'http://j-php.net'];
$monitor->on('update', function ($e) {
    var_dump($e);
});

$monitor->startWatch();

return;
//print_r($ideComponent->properties);

$deployer = new HotDeployer(function () {
    $webUi = new WebServerAppModule();
    /*$webUi->addModule(new SliderModule());
    $webUi->addModule(new AceEditorModule());*/
    $webUi->setupResources(
        './../wizard-web-ui/src-js/build/lib/dnext-engine.js',
        './../wizard-web-ui/src-js/build/lib/dnext-engine.min.css'
    );

    $app = new WebApplication();
    $app->addSettings('res://application.conf');
    $app->components[] = $webUi;
    $webUi->addUI(MainUI::class);

    $app->components[] = new WebAssets('/assets', './assets');
    $app->components[] = new WebDevModule();
    $app->launch();
}, function () {
    if (WebApplication::isInitialized()) {
        $app = WebApplication::current();
        $app->trigger(new Event('restart', $app));
        $app->shutdown();
    }
});

//$deployer->addFileWatcher('application.watcher');
$deployer->addDirWatcher('./src-php');
$deployer->addDirWatcher('../wizard-core/src-php');
$deployer->addDirWatcher('../wizard-web/src-php');
$deployer->addDirWatcher('../wizard-web-ui/src-php');
$deployer->run();