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
}