<?php

use framework\app\desktop\DesktopApplication;
use framework\app\desktop\WebDesktopAppModule;
use ui\MainUI;

$app = new DesktopApplication();
$appModule = new WebDesktopAppModule();
$appModule->setupResources(
    './../wizard-web-ui/src-js/build/lib/dnext-engine.js',
    './../wizard-web-ui/src-js/build/lib/dnext-engine.min.css'
);

$app->components->add($appModule);

$appModule->addUI(MainUI::class);

$app->launch();