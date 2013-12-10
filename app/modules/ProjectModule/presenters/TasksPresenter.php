<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule;

use TaskManager\Controls\Form;
use TaskManager\ProjectModule\Forms\CreateTaskForm;

class TasksPresenter extends BaseProjectPresenter {
	public function beforeRender()
	{
		parent::beforeRender();
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

	}

	public function actionDetail($id)
	{
		if(!$this->template->task = $this->taskFacade->getTaskById($id)) {
			$this->flashError('Tento úkol neexistuje');
			$this->redirect("Tasks:list");
		}
	}
}
 