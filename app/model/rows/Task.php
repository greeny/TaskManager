<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class Task extends ActiveRow {

	const STATUS_ACTIVE = 1;
	const STATUS_APPROVAL = 2;
	const STATUS_IN_PROGRESS = 3;
	const STATUS_FINISHED = 4;
	const STATUS_WONT_FIX = 5;
	const STATUS_NEED_HELP = 6;

	public function getParent()
	{
		return $this->ref('categories', 'category_id');
	}

	public function getOwner()
	{
		return $this->ref('users', 'user_id');
	}

	public function hasUserAccess($userId)
	{
		if((int) $this->user_id === (int) $userId) {
			return true;
		}
		return $this->related('task_users', 'task_id')->where('user_id', $userId)->fetch();
	}

	public function getAssignedUsers()
	{
		$return = array();
		foreach($this->related('task_users', 'task_id') as $user) {
			$return[] = $user->ref('users', 'user_id');
		}
		return $return;
	}

	public function getComments()
	{
		return $this->related('task_comments', 'task_id')->order('time DESC');
	}
}