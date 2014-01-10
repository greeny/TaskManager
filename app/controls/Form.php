<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Controls;

use Nette\Application\UI\Form as NForm;

class Form extends NForm {
	public function addPrimarySubmit($name, $caption = NULL)
	{
		return $this->addSubmit($name, $caption)
			->setAttribute('class', 'btn-primary');
	}

	public function addTextArea($name, $label = NULL, $cols = NULL, $rows = NULL)
	{
		return parent::addTextArea($name, $label, $cols, $rows)
			->setAttribute('class', 'span6')
			->setAttribute('rows', 10);
	}

	public function addTextEditor($name, $label)
	{
		return $this[$name] = new TextEditor($label);
	}
}