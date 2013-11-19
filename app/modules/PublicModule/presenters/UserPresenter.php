<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\PublicModule;

use Nette\Utils\Paginator;
use TaskManager\Model\Task;
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

	public function renderDetail($id)
	{
		if(!$this->template->u = $this->userFacade->getUserById($id)) {
			$this->redirect("Dashboard:default");
		}
	}

	public function renderTasks()
	{
		$this->template->tasks = $this->taskFacade->getUsersUnfinishedTasks($this->user->id)->order('priority DESC, name ASC');
	}

	public function renderMyTasks()
	{
		$this->template->tasks = $this->taskFacade->getTasksAddedByUser($this->user->id)->order('priority DESC, name ASC');
		$this->setView('tasks');
	}

	public function renderNotifications($page = 1)
	{
		$this->template->notifications = $this->notificationFacade->getNotificationsForUser($this->user->id, $this->createPaginator($page), $page);
	}

	protected function createPaginator($page = 1)
	{
		$paginator = new Paginator();
		$paginator->page = $page;
		$paginator->itemsPerPage = 20;
		return $this->template->paginator = $paginator;
	}

	public function handleSeen($id)
	{
		if($row = $this->notificationFacade->find($id)) {
			$row->setSeen();
		}
		$this->refresh();
	}

	public function handleDelete($id)
	{
		if($row = $this->notificationFacade->find($id)) {
			$row->delete();
		}
		$this->refresh();
	}
}