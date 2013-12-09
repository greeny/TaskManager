<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\Security;

use Nette\Security\Permission;

class Authorizator extends Permission {

	public function __construct()
	{
		$this->addRole('guest');
		$this->addRole('member', 'guest');
		$this->addRole('admin', 'member');
		$this->addRole('owner');

		// view, create, edit, delete
		// own, foreign

		$this->addResource('project');
		$this->addResource('task');
		$this->addResource('folder');
		$this->addResource('user');
		$this->addResource('admin');

		$this->allow('owner');

		$this->allow('member', array('task', 'folder'), array('view', 'create', 'edit', 'delete'));
		$this->allow('member', array('project', 'user'), array('view'));
		$this->allow('admin', array('task', 'folder', 'project', 'user'), array('view', 'create', 'edit', 'delete'));
	}
}
 