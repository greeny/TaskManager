<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\ProjectModule\Forms;

use TaskManager\Controls\BaseForm;
use TaskManager\Controls\Form;

class AddUserToProjectForm extends BaseForm {
	/** @var array of id => nick */
	public $users;

	protected function initializeForm(Form $form)
	{
		$form->addSelect('user_id', 'Přidat uživatele', $this->users)
			->setPrompt(' - Vyberte - ')
			->setRequired('Prosím vyber uživatele, kterého chceš přidat.');

		$form->addPrimarySubmit('add', 'Přidat');
	}
}
 