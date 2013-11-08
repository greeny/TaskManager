<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\Model;

class TaskUser extends ActiveRow {

	public function getUser()
	{
		return $this->ref('users', 'user_id');
	}
}
 