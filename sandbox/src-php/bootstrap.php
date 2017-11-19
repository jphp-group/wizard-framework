<?php

use controllers\MainController;
use framework\web\WebApplication;
use ui\MainUI;

$app = new WebApplication();
$app->addController(MainController::class);
$app->addUI(MainUI::class);
$app->launch();