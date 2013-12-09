<?php
/**
 * @author Tomáš Blatný
 */

namespace TaskManager\PublicModule\Forms;

use TaskManager\Controls\BaseForm;
use TaskManager\Controls\Form;

class SendChatMessageForm extends BaseForm {

	protected function initializeForm(Form $form)
	{
		$form->addTextArea('message', 'Zpráva:')
			->setRequired('Prosím vyplň zprávu.')
			->setAttribute('style', 'width: 50%;');

		$form->addSubmit('send', 'Poslat')
			->setAttribute('class', 'btn-primary');
	}
}
 