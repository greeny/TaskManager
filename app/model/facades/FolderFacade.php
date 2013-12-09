<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\Model;

use Nette\ArrayHash;
use Nette\Database\Connection;

class FolderFacade extends Facade {
	/** @var \TaskManager\Model\Folders */
	protected $folders;

	/** @var \Nette\Database\Connection */
	protected $connection;

	public function __construct(Connection $connection, Folders $folders)
	{
		$this->folders = $folders;
		$this->connection = $connection;
	}

	/**
	 * @param $id
	 * @return \TaskManager\Model\Folder|null
	 */
	public function getFolderById($id)
	{
		return $this->folders->find($id);
	}

	public function renameFolder($id, ArrayHash $data)
	{
		return $this->folders->find($id)->update($data);
	}

	public function deleteFolder($id)
	{
		$this->connection->query('LOCK TABLES folders WRITE');
		$this->connection->beginTransaction();

		if(!$folder = $this->folders->find($id)) {
			$this->connection->query('UNLOCK TABLES');
			return FALSE;
		}

		$this->folders->findBy('left >= ?', $folder->left)->where('right <= ?', $folder->right)->delete();

		$this->connection->commit();
		$this->connection->query('UNLOCK TABLES');
		return TRUE;
	}

	public function createFolder($parent, ArrayHash $data)
	{
		$this->connection->query('LOCK TABLES folders WRITE');
		$this->connection->beginTransaction();

		if(!$folder = $this->folders->find($parent)) {
			$this->connection->query('UNLOCK TABLES');
			return FALSE;
		}
		$left = (int) $folder->left;

		$this->connection->query('UPDATE `folders` SET `right` = `right` + 2 WHERE `right` > ' . $left);
		$this->connection->query('UPDATE `folders` SET `left` = `left` + 2 WHERE `left` > ' . $left);

		$this->folders->insert(array(
			'name' => $data->name,
			'left' => $left + 1,
			'right' => $left + 2,
		));

		$this->connection->commit();
		$this->connection->query('UNLOCK TABLES');
		return TRUE;
	}
}