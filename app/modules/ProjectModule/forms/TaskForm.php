<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule\Forms;

use TaskManager\Controls\BaseForm;
use TaskManager\Controls\Form;

class TaskForm extends BaseForm {
	/** @var string */
	public $path = "/";

	protected function initializeForm(Form $form)
	{
		$form->addText('name', 'Jméno')
			->setRequired('Prosím zadejte jméno úkolu.')
			->setOption('description', 'Cesta: '.$this->path);

		$form->addTextArea('description', 'Popis');
	}
}
 