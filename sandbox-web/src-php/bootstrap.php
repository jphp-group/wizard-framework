<?php

use bundle\aceeditor\AceEditorModule;
use bundle\slider\SliderModule;
use framework\app\web\WebServerAppModule;
use framework\core\Event;
use framework\web\HotDeployer;
use framework\web\WebApplication;
use framework\web\WebAssets;
use framework\web\WebDevModule;
use ui\MainUI;

$deployer = new HotDeployer(function () {
    $webUi = new WebServerAppModule();
    /*$webUi->addModule(new SliderModule());
    $webUi->addModule(new AceEditorModule());*/
    $webUi->setupResources(
        './../wizard-web-ui/src-js/build/lib/dnext-engine.js',
        './../wizard-web-ui/src-js/build/lib/dnext-engine.min.css'
    );

    $app = new WebApplication();
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