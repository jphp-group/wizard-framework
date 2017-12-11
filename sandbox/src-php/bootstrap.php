<?php

use framework\web\HotDeployer;
use framework\web\WebApplication;
use php\lang\Thread;
use ui\MainUI;

$deployer = new HotDeployer(function () {
    $app = new WebApplication();
    $app->enableUiSupport(
        './../web/src-js/build/lib/dnext-engine.js',
        './../web/src-js/build/lib/dnext-engine.min.css'
    );

    $app->addUI(MainUI::class);
    $app->launch();
}, function () {
    $app = WebApplication::current();
    $app->redeploy();
});

$deployer->addDirWatcher('./src-php');
$deployer->addDirWatcher('../web/src-php');
$deployer->run();