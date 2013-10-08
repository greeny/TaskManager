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

	public function renderProjects($page = 1)
	{
		$this->template->projects = $this->taskFacade->getProjects($this->user->id, $page);
	}

	public function renderProject($id)
	{
		if(!$this->template->project = $this->taskFacade->getProject($id))
		{
			$this->flashError('Tento projekt neexistuje.');
			$this->redirect('projects');
		}
	}

	public function renderCategories($id = NULL, $page = 1)
	{
		if($id === NULL) {
			$this->template->categories = $this->taskFacade->getCategories($this->user->id, $id, $page);
		}
	}

	public function renderCategory($id)
	{
		if(!$this->template->category = $this->taskFacade->getCategory($id))
		{
			$this->flashError('Tato kategorie neexistuje.');
			$this->redirect('projects');
		}
	}

	public function renderTasks($id = NULL, $page = 1)
	{
		if($id === NULL) {
			$this->template->tasks = $this->taskFacade->getTasks($this->user->id, $id, $page);
		}
	}

	public function renderTask($id)
	{
		if(!$this->template->task = $this->taskFacade->getTask($id))
		{
			$this->flashError('Tento úkol neexistuje.');
			$this->redirect('projects');
		}
	}
}