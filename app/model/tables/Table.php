<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

use Fabik\Database\Table as FTable;

abstract class Table extends FTable {
	public function query($sql)
	{
		return $this->getTable()->getConnection()->query($sql);
	}
}