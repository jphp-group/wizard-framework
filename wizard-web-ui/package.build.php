<?php

use packager\Event;
use php\io\File;
use php\lib\fs;
use php\lib\str;

/**
 * @jppm-task wizard:build
 */
function task_buildWebLib(Event $event) {
    // Install gradle
    Tasks::createDir('./gradle/wrapper');
    Tasks::createFile('./gradlew', str::replace(fs::get('res://gradle/gradlew'), "\r\n", "\n"));
    Tasks::createFile('./gradlew.bat', fs::get('res://gradle/gradlew.bat'));
    (new File('./gradlew'))->setExecutable(true);

    fs::copy('res://gradle/wrapper/gradle-wrapper.jar', './gradle/wrapper/gradle-wrapper.jar');
    fs::copy('res://gradle/wrapper/gradle-wrapper.properties', './gradle/wrapper/gradle-wrapper.properties');

    // Run Gradle
    (new GradlePlugin($event))->gradleProcess(["npmInstall", "installGulp", "buildWebLib"])
        ->inheritIO()
        ->startAndWait();

    // Publish package
    Tasks::run('publish', [], "yes");

    foreach ($event->package()->getAny('components', []) as $i => $module) {
        Tasks::runExternal("./components/$module", 'install', [], ...$event->flags());
        Tasks::runExternal("./components/$module", 'wizard:build', [], ...$event->flags());
    }
}