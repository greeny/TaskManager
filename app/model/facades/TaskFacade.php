<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class TaskFacade extends Facade {

	/** @var \TaskManager\Model\Tasks */
	protected $tasks;

	/** @var \TaskManager\Model\Categories */
	protected $categories;

	/** @var \TaskManager\Model\Projects */
	protected $projects;

	public function __construct(Tasks $tasks, Categories $categories, Projects $projects)
	{
		$this->tasks = $tasks;
		$this->categories = $categories;
		$this->projects = $projects;
	}

	public function getTasks($userId, $categoryId, $page = 1)
	{
		return $this->tasks->findBy('category_id', $categoryId)->limit(10, ($page - 1) * 10)->order('name ASC');
	}

	public function getCategories($userId, $projectId, $page = 1)
	{
		return $this->categories->findBy('project_id', $projectId)->limit(10, ($page - 1) * 10)->order('name ASC');
	}

	public function getProjects($userId, $page = 1)
	{
		return $this->projects->findAll()->limit(10, ($page - 1) * 10)->order('name ASC');
	}
}