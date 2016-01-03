<?php namespace Services\Messages;

use Services\Messages\MessagesInterface;
use InvalidArgumentException;

class Factory implements MessagesInterface
{
	protected $type;

	protected $messageTypes = ['alert', 'failure', 'success', 'info'];

	protected $id = false;

	protected $messages;

	public function __construct($type, array $messages, $id = false)
	{
		if (!$this->validType($type)) {
			throw new InvalidArgumentException('Invalid Message Type '.$type.'. Must be either alert, failure, success or info.');
		}

		$this->type = $type;

		$this->messages = $messages;

		if ($id !== false) {
			$this->id = $id;
		}
	}	

	public function hasId()
	{
		return !$this->id ? false : true;
	}

	private function validType($type)
	{
		return in_array($type, $this->messageTypes);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getMessages()
	{
		return $this->messages;
	}

	public function getType()
	{
		return $this->type;
	}
}