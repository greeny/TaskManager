<?php

namespace GeoCaching\Routing;

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
		$filterModule = array(
			'server' => 'Server',
		);
		$filterPresenter = array(
			'clanky' => 'Articles',
			'servery' => 'Servers',
			'uzivatele' => 'Users',
			'uzivatel' => 'User',
			'kesky' => 'Caches',
		);

		$filterAction = array(
			'seznam' => 'list',
			'registrace' => 'register',
			'prihlaseni' => 'login',
			'potvrdit' => 'verify',
			'propojit' => 'connect',
		);

		$router = new RouteList();
		$router[] = new Route('server/<server>/<presenter>/<action>[/<id>]', array(
			'module' => array(
				Route::VALUE => 'Server',
				Route::FILTER_TABLE => $filterModule,
			),
			'presenter' => array(
				Route::VALUE => 'Dashboard',
				Route::FILTER_TABLE => $filterPresenter,
			),
			'action' => array(
				Route::VALUE => 'default',
				Route::FILTER_TABLE => $filterAction,
			),
		));
		$router[] = new Route('<presenter>/<action>[/<id>]', array(
			'module' => array(
				Route::VALUE => 'Public',
				Route::FILTER_TABLE => $filterModule,
			),
			'presenter' => array(
				Route::VALUE => 'Dashboard',
				Route::FILTER_TABLE => $filterPresenter,
			),
			'action' => array(
				Route::VALUE => 'default',
				Route::FILTER_TABLE => $filterAction,
			),
		));
		return $router;
	}

}
