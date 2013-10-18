<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\PublicModule;

use TaskManager\Model\TaskFacade;

class UserPresenter extends BasePublicPresenter {
	/** @var \TaskManager\Model\TaskFacade */
	protected $taskFacade;

	public function actionTasks()
	{
		if(!$this->user->isLoggedIn()) {
			$this->redirect("Dashboard:default");
		}
	}

	public function inject(TaskFacade $taskFacade)
	{
		$this->taskFacade = $taskFacade;
	}

	public function renderDetail($id)
	{
		if(!$this->template->u = $this->userFacade->getUserById($id)) {
			$this->redirect("Dashboard:default");
		}
	}

	public function renderTasks()
	{
		$this->template->tasks = $this->taskFacade->getUsersUnfinishedTasks($this->user->id)->order('priority DESC, name ASC');
	}
}