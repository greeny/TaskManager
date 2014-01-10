<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule\Forms;

use TaskManager\Controls\Form;

class EditTaskForm extends TaskForm {

	protected function initializeForm(Form $form)
	{
		parent::initializeForm($form);

		$form->addPrimarySubmit('edit', 'Upravit');
	}
}
 