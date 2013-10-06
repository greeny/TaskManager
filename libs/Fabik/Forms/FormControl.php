<?php

namespace App\Controls;

use Nette\Application\UI\Control;
use GeoCaching\Controls\Form;

/**
 * @author Jan-Sebastian Fabík <honza@fabik.org>
 */
abstract class FormControl extends Control
{
	/** @var string */
	protected $cancelUrl;

	/** @var array of function(Form $sender); Occurs when the form is submitted and successfully validated */
	public $onSuccess;

	/** @var array of function(Form $sender); Occurs when the form is submitted and is not valid */
	public $onError;

	/** @var array of function(Form $sender); Occurs when the form is submitted */
	public $onSubmit;



	/** @param string */
	public function setCancelUrl($cancelUrl)
	{
		$this->cancelUrl = $cancelUrl;
	}

	protected abstract function initializeForm(Form $form);



	/**
	 * @return Form
	 */
	protected function createComponentForm()
	{
		$form = new Form;
		$this->initializeForm($form);
		$form->onSuccess[] = callback($this, 'onSuccess');
		$form->onError[] = callback($this, 'onError');
		$form->onSubmit[] = callback($this, 'onSubmit');
		return $form;
	}



	/**
	 * Fill-in with default values.
	 * @param  array|Traversable  values used to fill the form
	 * @param  bool     erase other default values?
	 * @return FormControl  provides a fluent interface
	 */
	public function setDefaults($values, $erase = FALSE)
	{
		$this['form']->setDefaults($values, $erase);
		return $this;
	}



	/**
	 * Renders the form.
	 * @return void
	 */
	public function render()
	{
		$templateFile = $this->getTemplateFile();
		$layoutFile = $this->getLayoutTemplateFile();

		$file = $templateFile !== NULL ? $templateFile : $layoutFile;
		$this->template->setFile($file);
		$this->template->form = $this['form'];
		$this->template->bootstrap = $layoutFile;
		$this->template->cancelUrl = $this->cancelUrl;
		$this->template->render();
	}



	/**
	 * Gets template file.
	 * @return string|NULL
	 */
	protected function getTemplateFile()
	{
		$refl = $this->getReflection();
		$file = dirname($refl->getFileName()) . '/' . $refl->getShortName() . '.latte';
		return file_exists($file) ? $file : NULL;
	}



	/**
	 * Gets layout template file.
	 * @return string
	 */
	protected function getLayoutTemplateFile()
	{
		$baseDir = dirname(dirname($this->getReflection()->getFileName()));
		return "$baseDir/templates/@form.latte";
	}
}
