<?php

use bundle\aceeditor\AceEditorModule;
use bundle\slider\SliderModule;
use framework\app\web\WebServerAppModule;
use framework\core\Event;
use framework\web\HotDeployer;
use framework\web\WebApplication;
use ui\MainUI;

$deployer = new HotDeployer(function () {
    $app = new WebApplication();
    $app->addSettings('res://application.conf');

    $webUi = new WebServerAppModule();
    $webUi->setApp($app);
    $webUi->addUI(MainUI::class);
    $webUi->addModule(new AceEditorModule());
    $webUi->addModule(new SliderModule());

    $app->launch();
}, function () {
    if (WebApplication::isInitialized()) {
        $app = WebApplication::current();
        $app->trigger(new Event('restart', $app));
        $app->shutdown();
    }
});

$deployer->addDirWatcher("./", true);
$deployer->run();