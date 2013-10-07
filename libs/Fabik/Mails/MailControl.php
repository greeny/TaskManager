<?php

namespace App\Controls;

use TaskManager\Controls\Mail;
use	Nette\NotSupportedException;
use Nette\Application\UI\Control;

/**
 * @author Jan-Sebastian Fabík <honza@fabik.org>
 */
abstract class MailControl extends Control
{
	/** @var Mail */
	protected $mail;

	protected abstract function initializeMail(Mail $mail);



	/** @return \TaskManager\Controls\Mail */
	public function getMail()
	{
		$mail = & $this->mail;
		if ($mail === NULL) {
			$mail = new Mail;
			$mail->setHtmlBody($this->getTemplate());
			$this->initializeMail($mail);
		}
		return $mail;
	}



	/**
	 * @param  string|NULL
	 * @return Nette\Templating\ITemplate
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);

		$templateFile = $this->getTemplateFile();
		$layoutFile = $this->getLayoutTemplateFile();

		$file = $templateFile !== NULL ? $templateFile : $layoutFile;
		$template->setFile($file);
		$template->mail = $this->mail;
		$template->bootstrap = $layoutFile;

		return $template;
	}



	/**
	 * Renders the control.
	 * @return void
	 */
	public function render()
	{
		throw new NotSupportedException;
	}



	/**
	 * Sends the message.
	 * @return void
	 */
	public function send()
	{
		$this->getMail()->send();
	}



	/**
	 * Gets template file.
	 * @return string|NULL
	 */
	protected function getTemplateFile()
	{
		$refl = $this->getReflection();
		$file = dirname($refl->getFileName()) . '/' . lcfirst($refl->getShortName()) . '.latte';
		return file_exists($file) ? $file : NULL;
	}



	/**
	 * Gets layout template file.
	 * @return string
	 */
	protected function getLayoutTemplateFile()
	{
		$baseDir = dirname(dirname($this->getReflection()->getFileName()));
		return "$baseDir/templates/@mail.latte";
	}
}
