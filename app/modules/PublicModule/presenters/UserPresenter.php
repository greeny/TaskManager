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

	public function renderTasks()
	{
		$this->template->tasks = $this->taskFacade->getUsersTasks($this->user->id);
	}
}