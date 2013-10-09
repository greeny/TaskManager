<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class Category extends ActiveRow {
	public function getTasks()
	{
		return $this->related('tasks', 'category_id');
	}

	public function getParent()
	{
		return $this->ref('projects', 'project_id');
	}
}