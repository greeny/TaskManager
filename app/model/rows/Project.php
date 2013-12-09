<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class Project extends ActiveRow {
	const ACCESS_PUBLIC = 1;
	const ACCESS_PRIVATE = 2;

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

	public function getAssignedUsers()
	{
		$return = array();
		foreach($this->related('project_access', 'project_id') as $row) {
			$return[] = $row->user;
		}
		return $return;
	}

	public function getFolderTree()
	{
		$root = $this->ref('folders', 'folder_id');

		/** @var \Fabik\Database\Selection $foldersTable */
		$foldersTable = $root->getTable();
		$folders = $foldersTable->createSelectionInstance()->where('(`left` >= ?) AND (`right` <= ?)', $root->left, $root->right)->order('left ASC');

		$stack = new \SplStack();
		$root = NULL;
		$current = NULL;

		foreach($folders as $folder) {
			$stack->push($folder);
			if($root === NULL) {
				$current = $root = new TreeItem($folder);
			} else {
				while(!$current->isChild($folder)) {
					if(!$current = $current->getParent()) {
						break 2;
					}
				}
				$current = $current->addChild($stack->pop());
			}
		}

		return $root;
	}
}