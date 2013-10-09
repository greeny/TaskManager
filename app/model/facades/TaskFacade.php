<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

use Nette\ArrayHash;
use Nette\DateTime;

class TaskFacade extends Facade {

	/** @var \TaskManager\Model\Tasks */
	protected $tasks;

	/** @var \TaskManager\Model\Categories */
	protected $categories;

	/** @var \TaskManager\Model\Projects */
	protected $projects;

	/** @var \TaskManager\Model\Users */
	protected $users;

	/** @var \TaskManager\Model\Groups */
	protected $groups;

	public function __construct(Tasks $tasks, Categories $categories, Projects $projects, Users $users, Groups $groups)
	{
		$this->tasks = $tasks;
		$this->categories = $categories;
		$this->projects = $projects;
		$this->users = $users;
		$this->groups = $groups;
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

	public function getTask($userId, $id)
	{
		return $this->tasks->find($id);
	}

	public function getCategory($userId, $id)
	{
		return $this->categories->find($id);
	}

	public function getProject($userId, $id)
	{
		return $this->projects->find($id);
	}

	public function addProject(ArrayHash $data)
	{
		$array = array(
			'name' => $data->name,
			'description' => $data->description,
		);
		if($data->id) {
			$row = $this->projects->find($data->id);
			return ($row ? $row->update($array) : null);
		} else {
			return $this->projects->create($array);
		}
	}

	public function addCategory(ArrayHash $data)
	{
		$array = array(
			'name' => $data->name,
			'description' => $data->description,
			'project_id' => $data->project_id,
		);
		if($data->id) {
			$row = $this->categories->find($data->id);
			return ($row ? $row->update($array) : null);
		} else {
			return $this->categories->create($array);
		}
	}

	public function addTask(ArrayHash $data, $userId)
	{
		$user = $this->users->find($data->user);
		$group = $this->groups->find($data->group);
		$array = array(
			'name' => $data->name,
			'user_id' => $userId,
			'description' => $data->description,
			'category_id' => $data->category_id,
			'assigned_user_id' => $user ? $user->id : NULL,
			'assigned_group_id' => $group ? $group->id : NULL,
			'status' => Task::STATUS_ACTIVE,
			'term' => DateTime::createFromFormat('d.m.Y', $data->term),
		);
		if($data->id) {
			$row = $this->tasks->find($data->id);
			return ($row ? $row->update($array) : null);
		} else {
			return $this->tasks->create($array);
		}
	}

	public function deleteProject($id)
	{
		$row = $this->projects->find($id);
		if($row) $row->delete();
	}

	public function deleteCategory($id)
	{
		$row = $this->categories->find($id);
		if($row) $row->delete();
	}

	public function deleteTask($id)
	{
		$row = $this->tasks->find($id);
		if($row) $row->delete();
	}

	public function getUsersArray()
	{
		return $this->users->findBy('verified', 1)->order('nick ASC')->fetchPairs('id', 'nick');
	}

	public function getGroupsArray()
	{
		return $this->groups->findAll()->order('name ASC')->fetchPairs('id', 'name');
	}
}