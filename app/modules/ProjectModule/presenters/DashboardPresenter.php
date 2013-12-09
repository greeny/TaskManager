<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule;

use TaskManager\Controls\Form;
use TaskManager\ProjectModule\Forms\AddUserToProjectForm;

class DashboardPresenter extends BaseProjectPresenter {
	public function renderDefault()
	{
		$this->template->taskCount = $this->projectFacade->getTasksInProject($this->projectId)->count();
		$this->template->folderCount = ((int)$this->projectFacade->getFoldersInProject($this->projectId)->count() - 1);
	}

	public function createComponentAddUserToProjectForm()
	{
		$form = new AddUserToProjectForm();
		$form->users = $this->projectFacade->getUsersAddableToProject($this->projectId);
		$form->onSuccess[] = $this->addUserToProjectFormSuccess;
		return $form;
	}

	public function addUserToProjectFormSuccess(Form $form)
	{
		$this->projectFacade->addUserToProject($form->getValues()->user_id, $this->projectId);
		$this->flashSuccess('Uživatel byl přidán');
		$this->redirect('Dashboard:default');
	}

	public function handleDeleteUser($user)
	{
		$this->projectFacade->removeUserFromProject($user, $this->projectId);
		$this->flashSuccess('Uživatel byl odebrán');
		$this->refresh();
	}
}
 