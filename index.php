<?php

define("ROOT_PATH", __DIR__);

if(file_exists('.maintenance')) require '.maintenance.php';

// Let bootstrap create Dependency Injection container.
$container = require __DIR__ . '/app/bootstrap.php';

// Run application.
$container->application->run();
