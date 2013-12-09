<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\Model;

use Fabik\Database\DuplicateEntryException;
use Nette\ArrayHash;
use Nette\Database\Connection;
use Nette\InvalidArgumentException;

class ProjectFacade extends Facade {
	/** @var \Nette\Database\Connection */
	protected $connection;

	/** @var \TaskManager\Model\Projects */
	protected $projects;

	/** @var \TaskManager\Model\Folders */
	protected $folders;

	/** @var \TaskManager\Model\ProjectAccess */
	protected $projectAccess;

	/** @var \TaskManager\Model\Users*/
	protected $users;

	/** @var \TaskManager\Model\Tasks */
	protected $tasks;

	public function __construct(Connection $connection, Users $users, Projects $projects, Folders $folders, ProjectAccess $projectAccess, Tasks $tasks)
	{
		$this->connection = $connection;
		$this->projects = $projects;
		$this->folders = $folders;
		$this->projectAccess = $projectAccess;
		$this->users = $users;
		$this->tasks = $tasks;
	}

	/**
	 * @param int $id
	 * @return null|\TaskManager\Model\Project
	 */
	public function getProjectById($id)
	{
		return $this->projects->find($id);
	}

	/**
	 * @param int $userId
	 * @return \TaskManager\Model\Projects
	 */
	public function getProjectsVisibleByUser($userId)
	{
		$accessible = array_values($this->projectAccess->findBy('user_id', $userId)->fetchPairs('id', 'id'));
		return $this->projects->findBy('(`access_type` = ?) OR (`id` IN ?) OR (`user_id` = ?)', Project::ACCESS_PUBLIC, $accessible, $userId);
	}

	/**
	 * @param ArrayHash $data
	 * @param int       $userId
	 * @return \TaskManager\Model\Project
	 * @throws \Fabik\Database\DuplicateEntryException
	 */
	public function createProject(ArrayHash $data, $userId)
	{
		if($this->projects->findOneBy('name', $data->name)) {
			throw new DuplicateEntryException("Projekt '$data->name' již existuje.");
		}

		$this->connection->query('LOCK TABLES projects WRITE, folders WRITE');
		$this->connection->beginTransaction();

		$max = 1 + (int) $this->folders->findAll()->aggregation('COALESCE(MAX(`right`), 0)');

		$rootFolder = $this->folders->create(array(
			'name' => $data->name.'_root_folder',
			'left' => $max,
			'right' => $max + 1,
		));

		$return = $this->projects->create(array(
			'user_id' => $userId,
			'folder_id' => $rootFolder->id,
			'name' => $data->name,
			'time' => Time(),
			'access_type' => in_array($data->access_type, array(Project::ACCESS_PUBLIC, Project::ACCESS_PRIVATE)) ? $data->access_type : Project::ACCESS_PUBLIC,
		));

		$this->connection->commit();
		$this->connection->query('UNLOCK TABLES');

		return $return;
	}

	public function editProject($id, ArrayHash $data)
	{
		$this->projects->find($id)->update($data);
	}

	public function deleteProject($id)
	{
		if(!$project = $this->projects->find($id)) {
			throw new InvalidArgumentException("Tento projekt neexistuje.");
		}

		$this->connection->query('LOCK TABLES projects WRITE, folders WRITE');
		$this->connection->beginTransaction();

		if($rootFolder = $this->folders->find($project->folder_id)) {
			$this->folders->findBy('left >= ?', $rootFolder->left)->where('right <= ?', $rootFolder->right)->delete();
		}
		$project->delete();
		$this->connection->query('UPDATE `folders` SET `right` = `right` - 2, `left` = `left` - 2 WHERE `left` >= '.(int)$rootFolder->left);

		$this->connection->commit();
		$this->connection->query('UNLOCK TABLES');
	}

	public function createFolder($folderId, ArrayHash $data)
	{
		if(!$folder = $this->folders->find($folderId)) {
			throw new InvalidArgumentException("Tato složka neexistuje.");
		}

		$this->connection->query('LOCK TABLES folders WRITE');
		$this->connection->beginTransaction();

		$this->connection->query('UPDATE `folders` SET `right` = `right` + 2 WHERE `right` >= '.(int)$folder->right);

		$this->folders->insert(array(
			'name' => $data->name,
			'left' => $folder->right,
			'right' => $folder->right + 1,
		));

		$this->connection->commit();
		$this->connection->query('UNLOCK TABLES');
	}

	public function getUsersAddableToProject($projectId)
	{
		$users = $this->users->findAll()->order('nick ASC')->fetchPairs('id', 'nick');
		$project = $this->projects->find($projectId);
		unset($users[$project->user_id]);
		/** @var $project \TaskManager\Model\Project */
		foreach($project->getAssignedUsers() as $user) {
			unset($users[$user->id]);
		}
		return $users;
	}

	public function addUserToProject($userId, $projectId)
	{
		try {
			$this->projectAccess->insert(array(
				'user_id' => $userId,
				'project_id' => $projectId,
			));
		} catch(DuplicateEntryException $e) {}
	}

	public function removeUserFromProject($userId, $projectId)
	{
		$this->projectAccess->findBy('user_id', $userId)->where('project_id', $projectId)->delete();
	}

	public function getFoldersInProject($project)
	{
		if(!$project = $this->projects->find($project)) {
			return array();
		}
		$root = $this->folders->find($project->folder_id);
		return $this->folders->findBy('left >= ?', $root->left)->where('right <= ?', $root->right);
	}

	public function getTasksInProject($projectId)
	{
		return $this->tasks->findBy('folder_id', $this->getFolderIds($projectId));
	}

	public function getFolderIds($project)
	{
		return array_values($this->getFoldersInProject($project)->fetchPairs('id', 'id'));
	}
}
