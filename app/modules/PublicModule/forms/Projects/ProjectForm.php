<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\PublicModule\Forms;

use TaskManager\Controls\BaseForm;
use TaskManager\Controls\Form;
use TaskManager\Model\Project;

class ProjectForm extends BaseForm {

	protected function initializeForm(Form $form)
	{
		$form->addText('name', 'Jméno projektu')
			->setRequired('Prosím zadej jméno projektu.');

		$form->addTextArea('description', 'Popis');

		$form->addSelect('access_type', 'Úroveň přístupu', array(
			Project::ACCESS_PUBLIC => 'Veřejný - je potřeba pouze přihlášení.',
			Project::ACCESS_PRIVATE => 'Soukromý - je potřeba být v seznamu uživatelů s přístupem.'
		))
			->setDefaultValue(Project::ACCESS_PUBLIC)
			->setRequired('Prosím zadej úroveň přístupu.');
	}
}
