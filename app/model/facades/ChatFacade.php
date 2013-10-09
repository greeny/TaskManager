<?php
/**
 * @author Tomáš Blatný
 */
namespace TaskManager\Model;

class ChatFacade extends Facade {
	/** @var \TaskManager\Model\Chats */
	protected $chats;

	public function __construct(Chats $chats)
	{
		$this->chats = $chats;
	}

	public function getChats($page = 1)
	{
		return $this->chats->findAll()->order('time DESC')->page($page, 10);
	}

	public function addChat($user, $message)
	{
		$this->chats->insert(array(
			'user_id' => $user,
			'message' => $message,
			'time' => Time(),
		));
	}
}