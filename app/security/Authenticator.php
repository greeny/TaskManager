<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Security;

use Nette\ArrayHash;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use TaskManager\Model\Users;

class Authenticator extends Object implements IAuthenticator {
	/** @var \TaskManager\Model\Users */
	protected $users;

	public function __construct(Users $users)
	{
		$this->users = $users;
	}

	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;
		if(!$user = $this->users->findOneBy('nick', $username)) {
			throw new AuthenticationException("Uživatel '$username' nenalezen.", Authenticator::IDENTITY_NOT_FOUND);
		}
		if(PasswordHasher::hash($username . '@' . $password, $user->salt) !== $user->password) {
			throw new AuthenticationException("Špatné heslo.", Authenticator::INVALID_CREDENTIAL);
		}
		if($user->verified == 0) {
			throw new AuthenticationException("Uživatel nemá ověřený účet.", Authenticator::FAILURE);
		}
		$user = ArrayHash::from($user->toArray());
		unset($user->password);
		unset($user->salt);
		unset($user->verified);
		return new Identity($user->id, $user->role, $user);
	}
}