<?php

use framework\app\desktop\DesktopApplication;
use framework\app\desktop\DesktopWebApplication;
use framework\app\desktop\WebDesktopAppModule;
use framework\app\web\WebServerAppModule;
use ui\MainUI;

$app = new DesktopWebApplication();
$app->addSettings('application.yml', 'yml');

$appModule = new WebServerAppModule();
$appModule->setupResources(
    './../wizard-web-ui/src-js/build/lib/dnext-engine.js',
    './../wizard-web-ui/src-js/build/lib/dnext-engine.min.css'
);

$app->components->add($appModule);

$appModule->addUI(MainUI::class);

$app->launch();