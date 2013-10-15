<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

use Nette\ArrayHash;
use Nette\Utils\Strings;
use TaskManager\Security\PasswordHasher;

class UserFacade extends Facade {
	/** @var \TaskManager\Model\Users */
	protected $users;

	public function __construct(Users $users)
	{
		$this->users = $users;
	}

	public function registerUser(ArrayHash $data)
	{
		if($this->users->findOneBy('nick', $data->nick)) {
			throw new RegisterException("Uživatel '{$data->nick}' již existuje.");
		}
		if($this->users->findOneBy('email', $data->email)) {
			throw new RegisterException("Uživatel s emailovou adresou '{$data->email}' již existuje.");
		}

		$salt = Strings::random(10, 'a-zA-Z0-9');

		$user = ArrayHash::from(array(
			'nick' => $data->nick,
			'password' => PasswordHasher::hash($data->nick . "@" . $data->password, $salt),
			'email' => $data->email,
			'salt' => $salt,
			'role' => 'member',
			'verified' => 0,
		));

		$this->users->insert($user);
		return $user;
	}

	public function getUserById($id)
	{
		return $this->users->find($id);
	}
}

class RegisterException extends \Exception {}