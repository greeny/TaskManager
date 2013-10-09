<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\PublicModule;

use Nette\Application\UI\Form;
use TaskManager\Model\ChatFacade;

class DashboardPresenter extends BasePublicPresenter {

	/** @var \TaskManager\Model\ChatFacade */
	protected $chatFacade;

	public function renderDefault($page = 1, $chat = false, $lastTime = 0)
	{
		if($chat) {
			$this->invalidateControl('chat');
			$this->invalidateControl('chatScript');
			$this->validateControl('content');
			$this->validateControl('navbar');
			$this->validateControl('essentials');
		}
		$this->template->chats = $this->chatFacade->getChats($page);
		$this->template->lastChatTime = $lastTime === 0 ? Time() : $lastTime;
		$this->template->newLastChatTime = Time();
		$this->template->page = $page;
	}

	public function inject(ChatFacade $chatFacade)
	{
		$this->chatFacade = $chatFacade;
	}

	public function createComponentSendChatMessageForm()
	{
		$form = new Form;

		$form->addText('message', 'Zpráva:')
			->setRequired('Prosím vyplň zprávu.');

		$form->addSubmit('send', 'Poslat');

		$form->onSuccess[] = $this->sendChatMessageFormSuccess;
		return $form;
	}

	public function sendChatMessageFormSuccess(Form $form)
	{
		if($this->user->isLoggedIn()) {
			$v = $form->getValues();
			$this->chatFacade->addChat($this->user->id, $v->message);
			$this->flashSuccess('Zpráva byla přidána');
			$this->refresh();
		} else {
			$this->flashError('Pro psaní do chatu se musíš přihlásit.');
			$this->refresh();
		}
	}
}