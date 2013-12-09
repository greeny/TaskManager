<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\PublicModule;

use Fabik\Database\DuplicateEntryException;
use TaskManager\Controls\Form;
use TaskManager\PublicModule\Forms\CreateProjectForm;
use TaskManager\PublicModule\Forms\EditProjectForm;

class ProjectsPresenter extends BasePublicPresenter {
	public function createComponentCreateProjectForm()
	{
		$form = new CreateProjectForm();
		$form->onSuccess[] = $this->createProjectFormSuccess;
		return $form;
	}

	public function createProjectFormSuccess(Form $form)
	{
		$v = $form->getValues();
		try{
			$project = $this->projectFacade->createProject($v, $this->user->id);
			$this->flashSuccess('Projekt byl vytvořen.');
			$this->redirect(':Public:Projects:detail', array('id' => $project->id));
		} catch(DuplicateEntryException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}
	}

	public function createComponentEditProjectForm()
	{
		$form = new EditProjectForm();
		$form->setDefaults($this->template->pr);
		$form->onSuccess[] = $this->editProjectFormSuccess;
		return $form;
	}

	public function editProjectFormSuccess(Form $form)
	{
		$v = $form->getValues();
		try{
			$this->projectFacade->editProject($this->params['id'], $v);
			$this->flashSuccess('Projekt byl vytvořen.');
			$this->redirect(':Public:Projects:detail', array('id' => $this->params['id']));
		} catch(DuplicateEntryException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}
	}

	public function actionDetail($id)
	{
		if(!$this->template->p = $this->projectFacade->getProjectById($id)) {
			$this->flashError('Projekt nenalezen.');
			$this->redirect(':Public:Projects:list');
		}
	}

	public function actionEdit($id)
	{
		if(!$this->template->pr = $this->projectFacade->getProjectById($id)) {
			$this->flashError('Projekt nenalezen.');
			$this->redirect(':Public:Projects:list');
		}
	}

	public function handleDelete($id)
	{
		$this->projectFacade->deleteProject($id);
		$this->flashSuccess('Projekt byl smazán.');
		$this->redirect(':Public:Projects:list');
	}
}
 