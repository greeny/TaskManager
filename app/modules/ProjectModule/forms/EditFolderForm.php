<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule\Forms;

use TaskManager\Controls\BaseForm;
use TaskManager\Controls\Form;

class EditFolderForm extends FolderForm {
	protected function initializeForm(Form $form)
	{
		parent::initializeForm($form);

		$form->addPrimarySubmit('edit', 'Přejmenovat');
	}
}
 