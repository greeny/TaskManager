<?php

namespace GeoCaching;

use GeoCaching\Controls\MailSender;
use GeoCaching\Model\UserFacade;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use GeoCaching\Templating\Helpers;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Presenter
{
	/** @var MailSender */
	protected $mailSender;

	/** @var \GeoCaching\Model\UserFacade */
	protected $userFacade;

	public function beforeRender()
	{
		parent::beforeRender();
		Helpers::prepareTemplate($this->template);
		if($this->user->isLoggedIn()) {
			$this->userFacade->updateLastLogin($this->user->id);
			$this->template->globalUser = $this->userFacade->getUserByName($this->user->identity->name);
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

	public function injectBase(MailSender $mailSender, UserFacade $userFacade)
	{
		$this->mailSender = $mailSender;
		$this->userFacade = $userFacade;
	}

	public function flashError($message)
	{
		return $this->flashMessage($message, 'danger');
	}

	public function flashSuccess($message)
	{
		return $this->flashMessage($message, 'success');
	}

	public function refresh() {
		$this->redirect('this');
	}

	public function createComponentPaginatorForm()
	{
		$form = new Form();
		$form->addText('page', 'Přejít na stranu:')
			->setRequired('Prosím vyplň stranu na kterou chceš přejít.')
			->setAttribute('placeholder', 'Stránka');

		$form->addSubmit('goto', 'Přejít');
		$form->onSuccess[] = $this->paginatorFormSuccess;
		return $form;
	}

	public function paginatorFormSuccess(Form $form)
	{
		$this->redirect('this', array($form->getValues()->page));
	}
}
