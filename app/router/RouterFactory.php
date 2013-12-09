<?php

namespace TaskManager\Routing;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();

		$router[] = new Route('<projectId [0-9]+>/<presenter>/<action>[/<id>]', array(
			'module' => 'Project',
			'presenter' => 'Dashboard',
			'action' => 'default',
		));

		$router[] = new Route('<presenter>/<action>[/<id>]', array(
			'module' => 'Public',
			'presenter' => 'Dashboard',
			'action' => 'default',
		));
		return $router;
	}

}
