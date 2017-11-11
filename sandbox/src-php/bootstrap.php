<?php

use controllers\MainController;
use framework\web\WebApplication;

$app = new WebApplication();
$app->addController(MainController::class);
$app->launch();