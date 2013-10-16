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

	public function getTaskCount($userId)
	{
		$count = 0;
		foreach($this->getCategories() as $category) {
			$count += $category->getTaskCount($userId);
		}
		return $count;
	}
}