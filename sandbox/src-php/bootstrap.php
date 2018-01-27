<?php

use bundle\aceeditor\AceEditorModule;
use bundle\slider\SliderModule;
use framework\core\Event;
use framework\web\HotDeployer;
use framework\web\WebApplication;
use framework\web\WebAssets;
use framework\web\WebUI;
use ui\MainUI;

$deployer = new HotDeployer(function () {
    $webUi = new WebUI();
    $webUi->addModule(new SliderModule());
    $webUi->addModule(new AceEditorModule());
    $webUi->setupResources(
        './../wizard-web-ui/src-js/build/lib/dnext-engine.js',
        './../wizard-web-ui/src-js/build/lib/dnext-engine.min.css'
    );

    $app = new WebApplication();
    $app->addModule($webUi);
    $webUi->addUI(MainUI::class);

    $app->addModule(new WebAssets('/assets', './assets'));
    $app->launch();
}, function () {
    $app = WebApplication::current();
    $app->trigger(new Event('restart', $app));
    $app->shutdown();
});

$deployer->addDirWatcher('./src-php');
$deployer->addDirWatcher('../wizard-web/src-php');
$deployer->addDirWatcher('../wizard-web-ui/src-php');
$deployer->run();