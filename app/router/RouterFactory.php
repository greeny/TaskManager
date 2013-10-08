<?php

namespace TaskManager\Routing;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


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

		$router[] = new Route('<action>[/<id [0-9]>]', array(
			'module' => 'Public',
			'presenter' => 'Board',
		));

		$router[] = new Route('<presenter>/<action>[/<id>]', array(
			'module' => 'Public',
			'presenter' => 'Dashboard',
			'action' => 'default',
		));
		return $router;
	}

}
