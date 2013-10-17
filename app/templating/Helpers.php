<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Templating;

use Nette\DateTime;
use Nette\Object;
use Nette\Templating\Template;
use Nette\Utils\Html;
use TaskManager\Model\Task;

class Helpers extends Object {

	public static function prepareTemplate(Template $template)
	{
		$template->registerHelper('role', function($text) {
			return (in_array($text, array('member'))) ? '' :
				Html::el('span', array('class' => 'label label-role-'.$text))->setText(ucfirst($text));
		});

		$template->registerHelper('nl2br', function($text) {
			return Html::el('')->setHtml(nl2br(\Nette\Templating\Helpers::escapeHtml($text)));
		});

		$template->registerHelper('status', function($text) {
			$class = '';
			if($text == Task::STATUS_ACTIVE) {
				return "";
			} else if($text == Task::STATUS_APPROVAL) {
				$class = 'approval'; $text = 'Čeká na ověření';
			} else if($text == Task::STATUS_FINISHED) {
				$class = 'finished'; $text = 'Hotový';
			} else if($text == Task::STATUS_IN_PROGRESS) {
				$class = 'inprogress'; $text = 'Ve vývoji';
			} else if($text == Task::STATUS_NEED_HELP) {
				$class = 'needhelp'; $text = 'Hledá se pomoc';
			} else if($text == Task::STATUS_WONT_FIX) {
				$class = 'wontfix'; $text = 'Nejde udělat';
			}

			return Html::el('span', array('class' => 'label label-status-'.$class))->setText($text);
		});

		$template->registerHelper('statusText', function($text) {
			$class = '';
			if($text == Task::STATUS_ACTIVE) {
				$text = 'Normální';
			} else if($text == Task::STATUS_APPROVAL) {
				$text = 'Čeká na ověření';
			} else if($text == Task::STATUS_FINISHED) {
				$text = 'Hotový';
			} else if($text == Task::STATUS_IN_PROGRESS) {
				$text = 'Ve vývoji';
			} else if($text == Task::STATUS_NEED_HELP) {
				$text = 'Hledá se pomoc';
			} else if($text == Task::STATUS_WONT_FIX) {
				$text = 'Nejde udělat';
			}

			return Html::el()->setText($text);
		});

		$template->registerHelper('score', function($text) {
			$text = (double) $text;
			$return = Html::el('span', array('data-tooltip' => '', 'title' => 'Hodnocení: ' . ($text ?: 'zatím nehodnoceno')));
			for($i = 1; $i <= 5; $i++) {
				$return->setHtml($return->getHtml() . Html::el('span', array('class' => 'glyphicon glyphicon-heart' . (($i > $text + 0.5)?'-empty':''))));
			}
			return $return;
		});

		$template->registerHelper('priority', function($text) {
			$text = (int) $text;
			return Html::el('span', array('class' => 'badge'))->setHtml($text)->addAttributes(array('data-tooltip' => '', 'title' => 'Priorita'));
		});

		$template->registerHelper('difficulty', function($text) {
			$text = (double) $text;
			$return = Html::el('span', array('data-tooltip' => '', 'title' => 'Obtížnost: ' . ($text ?: 'zatím nenastavena')));
			for($i = 1; $i <= 5; $i++) {
				$return->setHtml($return->getHtml() . Html::el('span', array('class' => 'glyphicon glyphicon-star' . (($i > $text + 0.5)?'-empty':''))));
			}
			return $return;
		});

		$template->registerHelper('time', function($text) {
			$text = (int) $text;
			return date('j.n.Y G:i:s', $text);
		});

		$template->registerHelper('date', function($dateTime) {
			return (!$dateTime ? 'nenastaven' : $dateTime->format('j.n.Y'));
		});

		$template->registerHelper('data', function($text, $type) {
			if($type === 'string') {
				return $text;
			} else if($type === 'int') {
				return (int) $text;
			} else if($type === 'email') {
				return Html::el('a')->addAttributes(array(
					'href' => 'mailto:'.$text,
				))->setText($text);
			} else if($type === 'web') {
				return Html::el('a')->addAttributes(array(
					'href' => (substr($text, 0, 4) === 'http' ? $text : 'http://'.$text),
				))->setText(substr($text, 0, 4) === 'http' ? $text : 'http://'.$text);
			} else {
				return $text;
			}
		});
	}
}