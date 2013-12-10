<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\Model;

use Nette\Object;

class TreeItem extends Object {
	/** @var \TaskManager\Model\TreeItem */
	protected $parent;

	/** @var array */
	protected $children = array();

	/** @var \TaskManager\Model\Folder */
	protected $folder;

	/** @var array */
	protected $tasks = array();

	public function __construct(Folder $folder, TreeItem $parent = NULL)
	{
		$this->folder = $folder;
		$this->parent = $parent;
	}

	/**
	 * @return TreeItem
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @return array of TreeItem
	 */
	public function getChildren()
	{
		usort($this->children, function(TreeItem $f1, TreeItem $f2) {
			return strcoll($f1->getRow()->name, $f2->getRow()->name);
		});
		return $this->children;
	}

	/**
	 * @return Folder
	 */
	public function getRow()
	{
		return $this->folder;
	}

	/**
	 * @param Folder $folder
	 * @return TreeItem
	 */
	public function addChild(Folder $folder)
	{
		return $this->children[] = new TreeItem($folder, $this);
	}

	/**
	 * @param Folder $folder
	 * @return bool
	 */
	public function isChild(Folder $folder)
	{
		return (bool) (($this->folder->left < $folder->left) && ($this->folder->right > $folder->right));
	}

	/**
	 * @param Task $task
	 * @return $this
	 */
	public function addTask(Task $task)
	{
		$this->tasks[] = $task;
		return $this;
	}

	/**
	 * @return array of Task
	 */
	public function getTasks()
	{
		usort($this->tasks, function(Task $t1, Task $t2) {
			return strcoll($t1->name, $t2->name);
		});
		return $this->tasks;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		$name = $this->folder->name;
		if(substr($name, -12) === '_root_folder') {
			return '';
		}
		$path = $name;
		$parent = $this;
		while(($parent = $parent->getParent()) != NULL) {
			if($parent->getParent() != NULL) {
				$path = $parent->getRow()->name . '/' . $path;
			}
		}
		return $path;
	}

	/**
	 * @return array of int
	 */
	public function getIds()
	{
		$ids = array();
		$ids[] = $this->folder->id;
		foreach($this->children as $child) {
			/** @var TreeItem $child */
			foreach($child->getIds() as $id) {
				$ids[] = $id;
			}
		}
		return $ids;
	}

	/**
	 * @param int $id
	 * @return TreeItem|null
	 */
	public function find($id)
	{
		$id = (int)$id;
		if($this->folder->id == $id) {
			return $this;
		}
		foreach($this->children as $child) {
			/** @var TreeItem $child */
			if($r = $child->find($id)) {
				return $r;
			}
		}
		return NULL;
	}
}
 