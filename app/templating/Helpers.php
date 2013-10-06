<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Templating;

use Nette\Object;
use Nette\Templating\Template;
use Nette\Utils\Html;
use GeoCaching\ServerModule\Model\Cache;

class Helpers extends Object {

	public static function prepareTemplate(Template $template)
	{
		$template->registerHelper('role', function($text) {
			return (in_array($text, array('guest', 'member'))) ? '' :
				Html::el('span', array('class' => 'label label-role-'.$text))->setText(ucfirst($text));
		});

		$template->registerHelper('status', function($text) {
			$text = (int) $text;
			if($text == Cache::ACTIVE) {
				return Html::el('span', array('class' => 'label label-status-active'))->setText("Aktivní");
			} else if($text == Cache::APPROVAL) {
				return Html::el('span', array('class' => 'label label-status-approval'))->setText("Čeká na schválení");
			} else if($text == Cache::DISABLED) {
				return Html::el('span', array('class' => 'label label-status-disabled'))->setText("Neaktivní");
			} else {
				return Html::el('span');
			}
		});

		$template->registerHelper('score', function($text) {
			$text = (double) $text;
			$return = Html::el('span', array('data-tooltip' => '', 'title' => 'Hodnocení: ' . ($text ?: 'zatím nehodnoceno')));
			for($i = 1; $i <= 5; $i++) {
				$return->setHtml($return->getHtml() . Html::el('span', array('class' => 'glyphicon glyphicon-heart' . (($i > $text + 0.5)?'-empty':''))));
			}
			return $return;
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