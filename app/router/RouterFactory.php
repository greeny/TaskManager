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

		$router[] = new Route('project[/<id>]', array(
			'module' => 'Public',
			'presenter' => 'Board',
			'action' => 'project',
		));

		$router[] = new Route('category[/<id>]', array(
			'module' => 'Public',
			'presenter' => 'Board',
			'action' => 'category',
		));

		$router[] = new Route('task[/<id>]', array(
			'module' => 'Public',
			'presenter' => 'Board',
			'action' => 'task',
		));

		$router[] = new Route('<presenter>/<action>[/<id>]', array(
			'module' => 'Public',
			'presenter' => 'Dashboard',
			'action' => 'default',
		));
		return $router;
	}

}
