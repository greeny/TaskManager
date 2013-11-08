<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\Model;

class Notification extends ActiveRow {

	public function getSourceUser()
	{
		return $this->ref('users', 'source_user_id');
	}

	public function getTargetUser()
	{
		return $this->ref('users', 'target_user_id');
	}

	public function setSeen()
	{
		if(!$this->seen) {
			$this->update(array('seen' => TRUE));
		}
	}
}
 