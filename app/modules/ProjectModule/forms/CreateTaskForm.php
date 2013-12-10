<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule\Forms;

use TaskManager\Controls\Form;

class CreateTaskForm extends TaskForm {

	protected function initializeForm(Form $form)
	{
		parent::initializeForm($form);

		$form->addPrimarySubmit('create', 'Vytvořit');
	}
}
 