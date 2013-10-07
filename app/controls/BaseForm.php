<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Controls;

use App\Controls\FormControl;

abstract class BaseForm extends FormControl {

	protected function getLayoutTemplateFile()
	{
		return ROOT_PATH."/app/modules/templates/@form.latte";
	}
}