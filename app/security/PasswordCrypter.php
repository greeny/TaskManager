<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Security;

use Nette\Object;

class PasswordCrypter extends Object {
	protected static $METHOD = "AES-256-CBC";
	protected static $KEY = 'fwf869acn3rv';
	protected static $IV = 'v4oh1d354r7n';

	public static function encrypt($password)
	{
		return base64_encode(openssl_encrypt($password, self::$METHOD, hash('sha512', self::$KEY), false, hash('crc32b', self::$IV).hash('crc32b', self::$IV)));
	}

	public static function decrypt($password)
	{
		return openssl_decrypt(base64_decode($password), self::$METHOD, hash('sha512', self::$KEY), false, hash('crc32b', self::$IV).hash('crc32b', self::$IV));
	}
}