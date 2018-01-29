<?php

use framework\core\Event;
use framework\web\HotDeployer;
use framework\web\WebApplication;
use framework\web\WebAssets;
use framework\web\WebUI;
use quester\GameUI;

$deployer = new HotDeployer(function () {
    $webUi = new WebUI();
    $webUi->setupResources(
        './../wizard-web-ui/src-js/build/lib/dnext-engine.js',
        './../wizard-web-ui/src-js/build/lib/dnext-engine.min.css'
    );

    $app = new WebApplication();

    $app->addModule($webUi);
    $webUi->addUI(GameUI::class);

    $app->addModule(new WebAssets('/assets', './assets'));
    $app->launch();
}, function () {
    $app = WebApplication::current();
    if ($app) {
        $app->trigger(new Event('restart', $app));
        $app->shutdown();
    }
});

$deployer->addDirWatcher('./src-php');
$deployer->addDirWatcher('../wizard-core/src-php');
$deployer->addDirWatcher('../wizard-web/src-php');
$deployer->addDirWatcher('../wizard-web-ui/src-php');
$deployer->run();