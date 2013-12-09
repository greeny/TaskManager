<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule;

use TaskManager\BasePresenter;

abstract class BaseProjectPresenter extends BasePresenter {
	/**
	 * @var int
	 * @persistent
	 */
	public $projectId;

	/** @var \TaskManager\Model\Project */
	protected $project;

	public function startup()
	{
		parent::startup();
		if(!$this->user->isLoggedIn()) {
			$this->flashError('Musíš se přihlásit, abys mohl přistupovat k projektům.');
			$this->redirect(':Public:Dashboard:default');
		}
		if(!$this->project = $this->template->project = $this->projectFacade->getProjectById($this->projectId)) {
			$this->flashError('Tento projekt neexistuje.');
			$this->redirect(':Public:Projects:list');
		}
	}
}
 