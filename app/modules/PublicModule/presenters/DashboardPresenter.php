<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\PublicModule;

use Nette\Application\UI\Form;

class DashboardPresenter extends BasePublicPresenter {
	public function createComponentSendChatMessageForm()
	{
		$form = new Form;

		$form->addTextArea('message', 'Zpráva:')
			->setRequired('Prosím vyplň zprávu.');

		$form->addSubmit('send', 'Poslat');

		$form->onSuccess[] = $this->sendChatMessageFormSuccess;
		return $form;
	}

	public function sendChatMessageFormSuccess(Form $form)
	{
		$v = $form->getValues();
	}
}