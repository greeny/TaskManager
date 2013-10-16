<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

use Fabik\Database\DuplicateEntryException;
use Nette\ArrayHash;
use Nette\DateTime;
use Nette\Utils\Paginator;

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

	/** @var \TaskManager\Model\TaskUsers */
	protected $taskUsers;

	public function __construct(Tasks $tasks, Categories $categories, Projects $projects, Users $users, Groups $groups, TaskUsers $taskUsers)
	{
		$this->tasks = $tasks;
		$this->categories = $categories;
		$this->projects = $projects;
		$this->users = $users;
		$this->groups = $groups;
		$this->taskUsers = $taskUsers;
	}

	/** NEW CODE **/

	public function getProjects()
	{
		return $this->projects->findAll();
	}

	public function getCategoriesInProject($projectId)
	{
		return $this->categories->findBy('project_id', $projectId);
	}

	public function getTasksInProject($userId, $projectId) {
		$categoryIds = array_values($this->categories->findBy('project_id', $projectId)->fetchPairs('id', 'id'));
		return $this->removeTasksWithoutAccess($userId, $this->tasks->findBy('category_id', $categoryIds));
	}

	public function getTasksInCategory($userId, $categoryId)
	{
		return $this->removeTasksWithoutAccess($userId, $this->tasks->findBy('category_id', $categoryId));
	}

	public function getTask($userId, $taskId)
	{
		if(!$task = $this->tasks->find($taskId)) {
			throw new NotFoundException("Úkol nebyl nalezen.");
		} elseif(!$task->hasUserAccess($userId)) {
			throw new NotFoundException("Nemáš přístup k tomuto úkolu.");
		}
		return $task;
	}

	public function getCategory($categoryId)
	{
		if(!$category = $this->categories->find($categoryId)) {
			throw new NotFoundException("Kategorie nebyla nalezena.");
		}
		return $category;
	}

	public function getProject($projectId)
	{
		if(!$project = $this->projects->find($projectId)) {
			throw new NotFoundException("Projekt nebyl nalezen.");
		}
		return $project;
	}

	protected function removeTasksWithoutAccess($userId, $tasks)
	{
		$ids = array();
		foreach($tasks as $task) {
			if($task->hasUserAccess($userId)) {
				$ids[] = $task->id;
			}
		}
		return $this->tasks->findBy('id', $ids);
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
		$array = array(
			'name' => $data->name,
			'user_id' => $userId,
			'description' => $data->description,
			'category_id' => $data->category_id,
			'status' => Task::STATUS_ACTIVE,
			'term' => ($data->term === '' ? NULL : DateTime::createFromFormat('d.m.Y', $data->term)),
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

	public function getUsersTasks($userId)
	{
		$ids = array_values($this->taskUsers->findBy('user_id', $userId)->fetchPairs('task_id', 'task_id'));
		return $this->tasks->findBy('id', $ids);
	}

	public function addUserToTask($taskId, $userId)
	{
		try{
			$this->taskUsers->create(array(
				'task_id' => $taskId,
				'user_id' => $userId,
			));
		} catch(DuplicateEntryException $e) {}
	}

	public function deleteUserFromTask($taskId, $userId)
	{
		$row = $this->taskUsers->findBy('task_id', $taskId)->where('user_id', $userId)->fetch();
		if($row) $row->delete();
	}

	public function setStatus($id, $status)
	{
		$this->tasks->find($id)->update(array('status' => $status));
	}
}

class NotFoundException extends \Exception {}