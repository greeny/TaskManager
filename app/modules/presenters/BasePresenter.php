<?php

namespace TaskManager;

use Nette\Security\AuthenticationException;
use TaskManager\Controls\MailSender;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use TaskManager\Model\NotificationFacade;
use TaskManager\Model\RegisterException;
use TaskManager\Model\TaskFacade;
use TaskManager\Model\UserFacade;
use TaskManager\Templating\Helpers;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{
	/** @var \TaskManager\Model\UserFacade */
	protected $userFacade;

	/** @var MailSender */
	protected $mailSender;

	/** @var \TaskManager\Model\NotificationFacade */
	protected $notificationFacade;

	/** @var \TaskManager\Model\TaskFacade */
	protected $taskFacade;

	public function beforeRender()
	{
		parent::beforeRender();
		Helpers::prepareTemplate($this->template);
		/** @var \TaskManager\Model\User $u */
		$u = $this->userFacade->getUserById($this->user->id);
		if($u) {
			$this->template->taskCount = $u->countUnfinishedTasks();
			$this->template->myTaskCount = $this->taskFacade->countTasksAddedByUser($u->id);
			$this->template->notificationCount = $u->countNewNotifications();
			$this->template->sessionCount = $u->countSessions();
		}
	}

	public function handleLogout()
	{
		if($this->user->isLoggedIn()) {
			$this->user->logout(TRUE);
			$this->flashSuccess('Byl jsi odhlášen.');
		}
		$this->redirect(":Public:Dashboard:default");
	}

	public function injectBase(MailSender $mailSender, UserFacade $userFacade, NotificationFacade $notificationFacade, TaskFacade $taskFacade)
	{
		$this->mailSender = $mailSender;
		$this->userFacade = $userFacade;
		$this->notificationFacade = $notificationFacade;
		$this->taskFacade = $taskFacade;
	}

	public function flashError($message)
	{
		if($this->isAjax())
			$this->invalidateControl('flashes');
		return $this->flashMessage($message, 'danger');
	}

	public function flashSuccess($message)
	{
		return $this->flashMessage($message, 'success');
	}

	public function refresh() {
		$this->redirect('this');
	}

	public function createComponentRegisterForm()
	{
		$form = new Form();
		$form->addText('nick', 'Nick')
			->setRequired('Prosím zadej nick.')
			->addRule($form::PATTERN, 'Nick může obsahovat pouze písmena anglické abecedy, čísla a podtržítko, minimální délka je 3.', '[a-zA-Z0-9_]{3,255}');

		$form->addPassword('password', 'Heslo')
			->setRequired('Prosím zadej heslo.')
			->addRule($form::PATTERN, 'Heslo musí mít aspoň 3 znaky.', '.{3,}');

		$form->addPassword('password2', 'Ověření hesla')
			->addRule($form::EQUAL, 'Hesla se musejí shodovat.', $form['password']);

		$form->addText('email', 'Email')
			->setRequired('Zadejte prosím email.')
			->addRule($form::EMAIL, 'Zadejte prosím platný email.');

		$form->addSubmit('register', 'Registrovat se')
			->setAttribute('class', 'btn-primary');

		$form->onSuccess[] = $this->registerFormSuccess;
		return $form;
	}

	public function registerFormSuccess(Form $form)
	{
		try {
			$this->userFacade->registerUser($form->getValues());
		} catch(RegisterException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}
		$this->flashSuccess("Registrace proběhla úspěšně.");
		$this->refresh();
	}

	public function createComponentLoginForm()
	{
		$form = new Form();
		$form->addText('nick', 'Nick')
			->setRequired('Prosím zadej nick.')
			->addRule($form::PATTERN, 'Nick může obsahovat pouze písmena anglické abecedy, čísla a podtržítko, minimální délka je 3.', '[a-zA-Z0-9_]{3,255}');

		$form->addPassword('password', 'Heslo')
			->setRequired('Prosím zadej heslo.')
			->addRule($form::PATTERN, 'Heslo musí mít aspoň 3 znaky.', '.{3,}');

		$form->addSubmit('login', 'Přihlásit se')
			->setAttribute('class', 'btn-primary');

		$form->onSuccess[] = $this->loginFormSuccess;
		return $form;
	}

	public function loginFormSuccess(Form $form)
	{
		try {
			$this->user->login($form->getValues()->nick, $form->getValues()->password);
			$this->flashSuccess('Přihlášení proběhlo úspěšně.');
			$this->refresh();
		} catch(AuthenticationException $e) {
			$this->flashError($e->getMessage());
			$this->refresh();
		}
	}

	public function createComponentPaginatorForm()
	{
		$form = new Form();
		$form->addText('page', 'Přejít na stranu:')
			->setRequired('Prosím vyplň stranu na kterou chceš přejít.')
			->setAttribute('placeholder', 'Stránka');

		$form->addHidden('maxPage');
		$form->addSubmit('goto', 'Přejít');
		$form->onSuccess[] = $this->paginatorFormSuccess;
		return $form;
	}

	public function paginatorFormSuccess(Form $form)
	{
		$v = $form->getValues();
		if($v->maxPage >= $v->page) {
			$this->redirect('this', array('page' => $v->page));
		} else {
			$this->redirect('this', array('page' => $v->maxPage));
		}
	}
}
