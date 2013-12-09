<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule\Forms;

use TaskManager\Controls\BaseForm;
use TaskManager\Controls\Form;

class FolderForm extends BaseForm {

	public $path;

	protected function initializeForm(Form $form)
	{
		$form->addText('name', 'Jméno složky')
			->setRequired('Prosím zadej jméno složky.')
			->setOption('description', 'Lokace: /' . $this->path);
	}
}
 