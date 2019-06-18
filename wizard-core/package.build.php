<?php

use packager\Event;

/**
 * @jppm-task wizard:build
 */
function task_build(Event $event) {
    Tasks::run('publish', [], "yes");
}