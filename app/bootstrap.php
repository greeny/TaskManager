<?php
use Fabik\Database\DatabaseExtension;

// Load Nette Framework or autoloader generated by Composer
require __DIR__ . '/../libs/Nette/loader.php';

$configurator = new Nette\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->enableDebugger(__DIR__ . '/../log');

// Specify folder for cache
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->onCompile[] = function($configurator, $compiler) {
	$compiler->addExtension('database', new DatabaseExtension);
};

// Enable RobotLoader - this will load all classes automatically
$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->addDirectory(__DIR__ . '/../libs/')
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');
$container = $configurator->createContainer();

return $container;
