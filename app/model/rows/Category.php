<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class Category extends ActiveRow {
	public function getTasksCount($userId)
	{
		return $this->getTable()->getConnection()->query("SELECT COUNT(*) FROM `tasks` `t`
			LEFT JOIN `task_permissions` `tp` ON `t`.`id` = `tp`.`task_id`
			WHERE (`t`.`user_id` = ".(int)$userId." OR `t`.`assigned_user_id` = ".(int)$userId." OR `tp`.`user_id` = ".(int)$userId.")
			AND `category_id` = ".(int)$this->id."
			ORDER BY `name` ASC")->fetchField(0);
	}

	public function getParent()
	{
		return $this->ref('projects', 'project_id');
	}
}