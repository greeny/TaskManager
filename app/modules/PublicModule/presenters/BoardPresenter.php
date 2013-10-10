<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\PublicModule;

use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
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
		$paginator = $this->createPaginator($page);
		$paginator->itemCount = count($projects = $this->taskFacade->getProjects($this->user->id));
		$this->template->projects = $projects->limit($paginator->length, $paginator->offset);
	}

	public function renderProject($id, $page = 1, $noerror = false)
	{
		$paginator = $this->createPaginator($page);
		if(!$this->template->project = $project = $this->taskFacade->getProject($this->user->id, $id))
		{
			if(!$noerror) $this->flashError('Tento projekt neexistuje.');
			$this->redirect('projects');
		}
		$paginator->itemCount = count($categories = $this->taskFacade->getCategories($this->user->id, $project->id));
		$this->template->categories = $categories->limit($paginator->length, $paginator->offset);
	}

	public function renderCategory($id, $page = 1, $noerror = false)
	{
		$paginator = $this->createPaginator($page);
		if(!$this->template->category = $category = $this->taskFacade->getCategory($this->user->id, $id))
		{
			if(!$noerror) $this->flashError('Tato kategorie neexistuje.');
			$this->redirect('projects');
		}
		$paginator->itemCount = count($tasks = $this->taskFacade->getTasks($this->user->id, $category->id));
		$this->template->tasks = $tasks->limit($paginator->length, $paginator->offset);
		$this->template->project = $category->getParent();
		$this->template->userArray = $this->taskFacade->getUsersArray();
	}

	public function renderTask($id, $noerror = false)
	{
		if(!$this->template->task = $task = $this->taskFacade->getTask($this->user->id, $id))
		{
			if(!$noerror) $this->flashError('Tento úkol neexistuje.');
			$this->redirect('projects');
		}
		$this->template->category = $category = $task->getParent();
		$this->template->project = $category->getParent();
	}

	public function createComponentAddProjectForm()
	{
		$form = new Form;
		$form->addHidden('id');

		$form->addText('name', 'Název')
			->setRequired('Prosím vyplň název.');

		$form->addTextArea('description', 'Popis');

		$form->addSubmit('submit', 'Přidat projekt');

		$form->onSuccess[] = $this->addProjectFormSuccess;

		return $form;
	}

	public function addProjectFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$this->taskFacade->addProject($v);
		if($v->id) {
			$this->flashSuccess('Projekt byl upraven.');
		} else {
			$this->flashSuccess('Projekt byl přidán.');
		}
		$this->refresh();
	}

	public function createComponentAddCategoryForm()
	{
		$form = new Form;
		$form->addHidden('id');

		$form->addText('name', 'Název')
			->setRequired('Prosím vyplň název.');

		$form->addTextArea('description', 'Popis');

		$form->addHidden('project_id', $this->params['id']);

		$form->addSubmit('submit', 'Přidat kategorii');

		$form->onSuccess[] = $this->addCategoryFormSuccess;

		return $form;
	}

	public function addCategoryFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$this->taskFacade->addCategory($v);
		if($v->id) {
			$this->flashSuccess('Kategorie byla upravena.');
		} else {
			$this->flashSuccess('Kategorie byla přidána.');
		}
		$this->refresh();
	}

	public function createComponentAddTaskForm()
	{
		$form = new Form;
		$form->addHidden('id');

		$form->addText('name', 'Název')
			->setRequired('Prosím vyplň název.');

		$form->addTextArea('description', 'Popis');

		$form->addSelect('user', 'Přiřazený uživatel', $this->taskFacade->getUsersArray())
			->setPrompt('-- Žádný --');

		$form->addSelect('group', 'Přiřazená skupina', $this->taskFacade->getGroupsArray())
			->setPrompt('-- Žádná --');

		$form->addText('term', 'Termín');

		$form->addHidden('category_id', $this->params['id']);

		$form->addSubmit('submit', 'Přidat úkol');

		$form->onSuccess[] = $this->addTaskFormSuccess;

		return $form;
	}

	public function addTaskFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$this->taskFacade->addTask($v, $this->user->id);
		if($v->id) {
			$this->flashSuccess('Úkol byl upraven.');
		} else {
			$this->flashSuccess('Úkol byl přidán.');
		}
		$this->refresh();
	}

	public function handleDelete($id, $type)
	{
		if($type === 'project') {
			$this->taskFacade->deleteProject($id);
			$this->flashSuccess('Projekt byl smazán.');
		} else if($type === 'category') {
			$this->taskFacade->deleteCategory($id);
			$this->flashSuccess('Kategorie byl smazána.');
		} else if($type === 'task') {
			$this->taskFacade->deleteTask($id);
			$this->flashSuccess('Úkol byl smazán.');
		}
		$this->redirect('this', array('noerror' => 1));
	}

	protected function createPaginator($page = 1)
	{
		$paginator = new Paginator();
		$paginator->page = $page;
		$paginator->itemsPerPage = 15;
		return $this->template->paginator = $paginator;
	}
}