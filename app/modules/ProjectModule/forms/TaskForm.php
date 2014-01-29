<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule\Forms;

use TaskManager\Controls\BaseForm;
use TaskManager\Controls\Form;
use TaskManager\Model\Task;

class TaskForm extends BaseForm {
	/** @var string */
	public $path = "/";

	protected function initializeForm(Form $form)
	{
		$form->addText('name', 'Jméno')
			->setRequired('Prosím zadejte jméno úkolu.')
			->setOption('description', 'Cesta: '.$this->path);

		$form->addTextEditor('description', 'Popis');

		$form->addSelect('access_type', 'Přístup', array(
			Task::ACCESS_PUBLIC => 'Veřejný - je potřeba pouze přihlášení.',
			Task::ACCESS_PRIVATE => 'Soukromý - je potřeba být v seznamu uživatelů s přístupem.',
		));
	}
}
 