<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\PublicModule;

use TaskManager\BasePresenter;

class BasePublicPresenter extends BasePresenter {

	public function startup()
	{
		parent::startup();
		if($this->name !== 'Public:Dashboard' && $this->name !== 'Public:User') {
			if(!$this->user->isLoggedIn()) {
				$this->flashError('Musíš se přihlásit, abys mohl přistupovat k projektům.');
				$this->redirect(':Public:Dashboard:default');
			}
			$this->template->projects = $this->projectFacade->getProjectsVisibleByUser($this->user->id);
		}
	}
}