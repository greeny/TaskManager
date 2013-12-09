<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\PublicModule\Forms;

use TaskManager\Controls\Form;

class EditProjectForm extends ProjectForm {

	protected function initializeForm(Form $form)
	{
		parent::initializeForm($form);

		$form->addPrimarySubmit('edit', 'Upravit projekt');
	}

}