<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class Project extends ActiveRow {
	public function getCategories()
	{
		return $this->related('categories', 'project_id');
	}
}