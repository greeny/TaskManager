<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\Model;

use Nette\Utils\Paginator;

class NotificationFacade extends Facade {

	protected $notifications;

	public function __construct(Notifications $notifications)
	{
		$this->notifications = $notifications;
	}

	public function getNotificationsForUser($userId, Paginator $paginator, $page = 1)
	{
		$a = $this->notifications->findBy('target_user_id', $userId);
		$paginator->setItemCount($a->count());
		return $a->order('time DESC')->page($page, $paginator->getItemsPerPage());
	}

	public function find($id)
	{
		return $this->notifications->find($id);
	}

	public function addNotification($sourceId, $targetId, $taskId, $text)
	{
		if($sourceId === NULL || $targetId === NULL || $taskId === NULL) {
			dump($sourceId, $targetId, $taskId, $text);
			die;
		}

		if((int)$sourceId !== (int)$targetId) {
			$this->notifications->insert(array(
				'source_user_id' => $sourceId,
				'target_user_id' => $targetId,
				'text' => $text,
				'task_id' => $taskId,
				'time' => Time(),
				'seen' => 0,
			));
		}
	}
}
 