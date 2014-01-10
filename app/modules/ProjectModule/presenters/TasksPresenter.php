<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule;

use Fabik\Database\DuplicateEntryException;
use TaskManager\Controls\Form;
use TaskManager\ProjectModule\Forms\CreateTaskForm;
use TaskManager\ProjectModule\Forms\EditTaskForm;

class TasksPresenter extends BaseProjectPresenter {
	public function startup()
	{
		parent::startup();
		$this->template->folderTree = $this->projectFacade->getTree($this->projectId, $this->user->id);
	}

	public function createComponentCreateTaskForm()
	{
		$form = new CreateTaskForm();
		$form->onSuccess[] = $this->createTaskFormSuccess;
		$form->path = "/" . $this->template->folderTree->find($this->params['id'])->getPath();
		return $form;
	}

	public function createTaskFormSuccess(Form $form)
	{
		try {
			$task = $this->taskFacade->createTask($form->getValues(), $this->user->id, $this->params['id']);
			$this->flashSuccess("Úkol byl vytvořen.");
			$this->redirect("Tasks:detail", $task->id);
		} catch(DuplicateEntryException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}
	}

	public function createComponentEditTaskForm()
	{
		$form = new EditTaskForm();
		$form->onSuccess[] = $this->editTaskFormSuccess;
		$form->path = "/" . $this->template->folderTree->find($this->template->task->folder_id)->getPath() . "/" . $this->template->task->name;
		$form->setDefaults($this->template->task);
		return $form;
	}

	public function editTaskFormSuccess(Form $form)
	{
		if($this->taskFacade->editTask($this->params['id'], $form->getValues())) {
			$this->flashSuccess('Úkol byl upraven.');
			$this->redirect('Tasks:detail', $this->params['id']);
		} else {
			$this->flashError('Tento úkol neexistuje.');
			$this->redirect("Tasks:list");
		}
	}

	public function actionDetail($id)
	{
		if(!$this->template->task = $this->taskFacade->getTaskById($id)) {
			$this->flashError('Tento úkol neexistuje');
			$this->redirect("Tasks:list");
		}
	}

	public function actionEdit($id)
	{
		if(!$this->template->task = $this->taskFacade->getTaskById($id)) {
			$this->flashError('Tento úkol neexistuje');
			$this->redirect("Tasks:list");
		}
	}

	public function actionCreate($id)
	{

	}
}
 