<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Controls;

use App\Controls\MailControl;

abstract class BaseMail extends MailControl {
	protected $subject;

	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function getSubject()
	{
		return $this->subject;
	}
}