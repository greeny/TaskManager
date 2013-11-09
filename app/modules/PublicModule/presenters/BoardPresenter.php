<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\PublicModule;

use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
use TaskManager\Model\NotFoundException;
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
		$paginator->itemCount = count($projects = $this->taskFacade->getProjects()->order('name ASC'));
		$this->template->projects = $projects->limit($paginator->length, $paginator->offset);
	}

	public function renderProject($id, $page = 1, $noerror = false)
	{
		$paginator = $this->createPaginator($page);
		try {
			$this->template->project = $project = $this->taskFacade->getProject($id);

		} catch(NotFoundException $e) {
			if(!$noerror) $this->flashError($e->getMessage());
			$this->redirect('projects');
		}
		$paginator->itemCount = count($categories = $this->taskFacade->getCategoriesInProject($project->id)->order('name ASC'));
		$this->template->categories = $categories->limit($paginator->length, $paginator->offset);
	}

	public function renderCategory($id, $page = 1, $noerror = false)
	{
		$paginator = $this->createPaginator($page);
		try {
			$this->template->category = $category = $this->taskFacade->getCategory($id);
		} catch(NotFoundException $e) {
			if(!$noerror) $this->flashError($e->getMessage());
			$this->redirect('projects');
		}

		$tasks = $this->taskFacade->getTasksInCategory($this->user->id, $id)->order('priority DESC, name ASC');
		$paginator->itemCount = count($tasks);
		$this->template->tasks = $tasks->limit($paginator->length, $paginator->offset);
		$this->template->project = $category->getParent();
		$this->template->userArray = $this->taskFacade->getUsersArray();
	}

	public function renderTask($id, $noerror = false, $page = 1)
	{
		try {
			$this->template->task = $task = $this->taskFacade->getTask($this->user->id, $id);
		} catch(NotFoundException $e) {
			if(!$noerror) $this->flashError($e->getMessage());
			$this->redirect('projects');
		}

		$paginator = $this->createPaginator($page);
		$paginator->setItemCount(count($task->comments));
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

		$form->addText('term', 'Termín')
			->setAttribute('autocomplete', 'off');

		$form->addSelect('priority', 'Priorita', array_combine($a = range(1,10), $a));

		$form->addHidden('category_id', $this->params['id']);

		$form->addSubmit('submit', 'Přidat úkol');

		$form->onSubmit[] = $this->addTaskFormSuccess;

		return $form;
	}

	public function addTaskFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$task = $this->taskFacade->addTask($v, $this->user->id);
		if($v->id) {
			$this->flashSuccess('Úkol byl upraven.');
		} else {
			$this->flashSuccess('Úkol byl přidán.');
		}
		if($task) {
			foreach($task->getAssignedUsers() as $user) {
				$this->notificationFacade->addNotification($this->user->id, $user->id, $task->id, 'upravil úkol');
			}
		}
		$this->notificationFacade->addNotification($this->user->id, $task->user->id, $task->id, 'upravil úkol');
		$this->refresh();
	}

	public function handleDelete($id, $type, $u = null)
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
		} else if($type === 'user') {
			$this->taskFacade->deleteUserFromTask($id, $u);
			$this->notificationFacade->addNotification($this->user->id, $u, $id, 'tě odstranil z úkolu');
			$this->flashSuccess('Uživatel byl odstraněn z úkolu.');
		}
		$this->redirect('this', array('noerror' => 1));
	}

	public function handleStatus($id, $status)
	{
		$this->taskFacade->setStatus($id, $status);
		$this->flashSuccess('Status byl nastaven.');

		$task = $this->taskFacade->getTaskById($id);
		if($task) {
			foreach($task->getAssignedUsers() as $user) {
				$this->notificationFacade->addNotification($this->user->id, $user->id, $id, 'nastavil status úkolu');
			}
		}
		$this->notificationFacade->addNotification($this->user->id, $task->user->id, $id, 'nastavil status úkolu');

		$this->refresh();
	}

	public function handlePriority($id, $priority)
	{
		$this->taskFacade->setPriority($id, $priority);
		$this->flashSuccess('Priorita byla nastavena.');

		$task = $this->taskFacade->getTaskById($id);
		if($task) {
			foreach($task->getAssignedUsers() as $user) {
				$this->notificationFacade->addNotification($this->user->id, $user->id, $id, 'nastavil prioritu úkolu');
			}
		}
		$this->notificationFacade->addNotification($this->user->id, $task->user->id, $id, 'nastavil prioritu úkolu');

		$this->refresh();
	}

	public function createComponentAddUserToTaskForm()
	{
		$form = new Form;
		$form->addHidden('task_id');

		$form->addSelect('user_id', 'Uživatel', $this->taskFacade->getUsersArray())
			->setPrompt('-- Vyber uživatele --')
			->setRequired('Vyber prosím uživatele.');

		$form->addSubmit('submit', 'Přidat uživatele');

		$form->onSuccess[] = $this->addUserToTaskFormSuccess;

		return $form;
	}

	public function addUserToTaskFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$this->taskFacade->addUserToTask($v->task_id, $v->user_id);
		$this->notificationFacade->addNotification($this->user->id, $v->user_id, $v->task_id, 'tě přidal k úkolu');
		$this->flashSuccess('Uživatel byl přidán k úkolu.');
		$this->refresh();
	}

	public function createComponentAddCommentForm()
	{
		$form = new Form;
		$form->addTextArea('text', 'Komentář');
		$form->addSubmit('send', 'Přidat komentář');
		$form->onSuccess[] = $this->addCommentFormSuccess;
		return $form;
	}

	public function addCommentFormSuccess(Form $form)
	{
		$v = $form->getValues();
		$this->taskFacade->addComment($this->user->id, $this->params['id'], $v->text);
		$task = $this->taskFacade->getTaskById($this->params['id']);
		if($task) {
			foreach($task->getAssignedUsers() as $user) {
				$this->notificationFacade->addNotification($this->user->id, $user->id, $task->id, 'okomentoval úkol');
			}
		}
		$this->notificationFacade->addNotification($this->user->id, $task->user->id, $task->id, 'okomentoval úkol');
		$this->flashSuccess('Komentář byl přidán.');
		$this->refresh();
	}

	protected function createPaginator($page = 1)
	{
		$paginator = new Paginator();
		$paginator->page = $page;
		$paginator->itemsPerPage = 15;
		return $this->template->paginator = $paginator;
	}
}