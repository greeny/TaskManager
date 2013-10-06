<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Controls;

use Nette\Mail\IMailer;
use Nette\Object;

class MailSender extends Object {
	/** @var \Nette\Mail\IMailer */
	protected $mailer;

	public function __construct(IMailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public function send(BaseMail $baseMail, $to = null)
	{
		$mail = $baseMail->getMail();
		$mail->setFrom('geocaching@gameteam.cz', 'GameTeam.cz GeoCaching');
		$mail->setSubject("[GameTeam.cz GeoCaching] " . $baseMail->getSubject());
		if($to !== null) {
			$mail->addTo($to);
		}
		$this->mailer->send($mail);
	}
}