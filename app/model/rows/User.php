<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class User extends ActiveRow {
	public function countTasks()
	{
		return $this->related('task_users', 'user_id')->count();
	}

	public function countUnfinishedTasks()
	{
		$count = 0;
		foreach($this->related('task_users', 'user_id') as $row) {
			$task = $row->ref('tasks', 'task_id');
			if($task->status != Task::STATUS_FINISHED) {
				$count++;
			}
		}
		return $count;
	}

	public function countSessions()
	{
		return $this->related('sessions', 'owner')->count();
	}

	public function countNewNotifications()
	{
		return $this->related('notifications', 'target_user_id')->where('seen', 0)->count();
	}
}