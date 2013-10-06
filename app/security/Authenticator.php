<?php
/**
 * @author Tomáš Blatný
 */
namespace GeoCaching\Security;

use GeoCaching\Model\Users;
use Nette\ArrayHash;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;

class Authenticator extends Object implements IAuthenticator {
	/** @var \GeoCaching\Model\Users */
	protected $users;

	public function __construct(Users $users)
	{
		$this->users = $users;
	}

	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;
		if(!$user = $this->users->findOneBy('name', $username)) {
			throw new AuthenticationException("Uživatel '$username' nenalezen.", Authenticator::IDENTITY_NOT_FOUND);
		}
		if(PasswordHasher::hash($username . '@' . $password, $user->salt) !== $user->password) {
			throw new AuthenticationException("Špatné heslo.", Authenticator::INVALID_CREDENTIAL);
		}
		if($user->email_verified == 0) {
			throw new AuthenticationException("Uživatel nemá ověřený email.", Authenticator::FAILURE);
		}
		$user = ArrayHash::from($user->toArray());
		unset($user->hash);
		unset($user->password);
		unset($user->salt);
		unset($user->verification_code);
		unset($user->email_verified);
		return new Identity($user->id, $user->role, $user);
	}
}