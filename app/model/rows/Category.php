<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class Category extends ActiveRow {
	public function getTaskCount($userId)
	{
		return count($this->getTasks($userId));
	}

	public function getTasks($userId)
	{
		$ids = array();
		foreach($tasks = $this->related('tasks', 'category_id') as $task) {
			if($task->hasUserAccess($userId)) {
				$ids[] = $task->id;
			}
		}
		return $this->related('tasks', 'category_id')->where('id', $ids);
	}

	public function getParent()
	{
		return $this->ref('projects', 'project_id');
	}
}