<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\PublicModule\Forms;

use TaskManager\Controls\Form;

class CreateProjectForm extends ProjectForm {

	protected function initializeForm(Form $form)
	{
		parent::initializeForm($form);

		$form->addPrimarySubmit('create', 'Vytvořit nový projekt');
	}

}
 