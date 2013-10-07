<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Security;

use Nette\Object;

class PasswordHasher extends Object{
	public static function hash($password, $salt) {
		return self::multiHash('sha512', $password, $salt, 5);
	}

	protected static function multiHash($hash, $password, $salt, $count = 1) {
		return ($count > 1) ?
			self::multiHash($hash, self::simpleHash($hash, $password, $salt), $salt, --$count) :
			self::simpleHash($hash, $password, $salt);
	}

	protected static function simpleHash($hash, $password, $salt)
	{
		return hash($hash, $salt.$password.$salt.$salt);
	}
}