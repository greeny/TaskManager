<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\PublicModule;

use TaskManager\Model\TaskFacade;

class BoardPresenter extends BasePublicPresenter {

	/** @var \TaskManager\Model\TaskFacade */
	protected $taskFacade;

	public function inject(TaskFacade $taskFacade)
	{
		$this->taskFacade = $taskFacade;
	}

	public function renderProject($id = NULL, $page = 1)
	{
		if($id === NULL) {
			$this->template->projects = $this->taskFacade->getProjects($this->user->id, $page);
		}
	}

	public function renderCategory($id = NULL, $page = 1)
	{
		if($id === NULL) {
			$this->template->categories = $this->taskFacade->getCategories($this->user->id, $projectId, $page);
		}
	}

	public function renderTask($id = NULL, $page = 1)
	{
		if($id === NULL) {
			$this->template->tasks = $this->taskFacade->getTasks($this->user->id, $taskId, $page);
		}
	}
}