<?php

use controllers\MainController;
use framework\web\WebApplication;
use ui\MainUI;

$app = new WebApplication();
$app->enableUiSupport(
    './../web/src-js/build/lib/dnext-engine.js',
    './../web/src-js/build/lib/dnext-engine.min.css'
);

//$app->addController(MainController::class);
$app->addUI(MainUI::class);
$app->launch();