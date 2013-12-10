<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule;

use TaskManager\Controls\Form;
use TaskManager\Model\FolderFacade;
use TaskManager\ProjectModule\Forms\CreateFolderForm;
use TaskManager\ProjectModule\Forms\EditFolderForm;

class FoldersPresenter extends BaseProjectPresenter {

	/** @var \TaskManager\Model\FolderFacade */
	protected $folderFacade;

	public function inject(FolderFacade $folderFacade)
	{
		$this->folderFacade = $folderFacade;
	}

	public function beforeRender()
	{
		parent::beforeRender();
		$this->template->folderTree = $this->projectFacade->getTree($this->projectId, $this->user->id);
	}

	public function createComponentCreateFolderForm()
	{
		$form = new CreateFolderForm;
		$form->path = $this->project->getFolderTree()->find($this->params['id'])->getPath();
		$form->onSuccess[] = $this->createFolderFormSuccess;
		return $form;
	}

	public function createFolderFormSuccess(Form $form)
	{
		if($this->folderFacade->createFolder($this->params['id'], $form->getValues())) {
			$this->flashSuccess('Složka byla vytvořena');
		} else {
			$this->flashError('Rodičovská složka neexistuje.');
		}
		$this->redirect('Folders:default');
	}

	public function renderCreate($id)
	{
		$this->template->id = $id;
	}

	public function handleDelete($id)
	{
		if($this->folderFacade->deleteFolder($id)) {
			$this->flashSuccess('Složka byla smazána.');
		} else {
			$this->flashError('Tato složka neexistuje.');
		}
		$this->refresh();
	}

	public function actionRename($id)
	{
		if(!$this->template->folder = $this->folderFacade->getFolderById($id)) {
			$this->flashError('Tato složka neexistuje');
			$this->redirect('Dashboard:default');
		}
	}

	public function createComponentEditFolderForm()
	{
		$form = new EditFolderForm();
		$form->path = $this->project->getFolderTree()->find($this->params['id'])->getPath();
		$form->setDefaults($this->template->folder);
		$form->onSuccess[] = $this->editFolderFormSuccess;
		return $form;
	}

	public function editFolderFormSuccess(Form $form)
	{
		$this->folderFacade->renameFolder($this->params['id'], $form->getValues());
		$this->flashSuccess('Složka byla přejenována.');
		$this->refresh();
	}

	public function renderList($id)
	{
		$this->template->folder = $this->folderFacade->getFolderById($id);
		$this->template->tasks = $this->projectFacade->getTasksInFolder($id, $this->user->id);
	}
}
 