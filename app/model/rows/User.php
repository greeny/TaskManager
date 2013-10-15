<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class User extends ActiveRow {
	public function countTasks()
	{
		return $this->related('tasks', 'assigned_user_id')->count();
	}

	public function countSessions()
	{
		return $this->related('sessions', 'owner')->count();
	}
}