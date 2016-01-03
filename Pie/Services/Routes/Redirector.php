<?php namespace Services\Routes;

use Helpers\Redirector as ReDir;
use Services\Messages\Facade;
use Services\Messages\Collection;
use Services\Messages\Builder as Messages;
use Services\Messages\Accessor;

class Redirector
{
	use ReDir;

	public function __construct()
	{
		$this->accessor = new Accessor();
	}

	public function messages($key, array $messages)
	{
		foreach ($messages as $k => $m) {
			$messageObjects[] = Facade::make($k, $m['messages'], $this->id($m));
		}

		$collection = new Collection($messageObjects);

		$builder = new Messages($collection);

		try {
			$builder->set($key);	
		} 
		catch(Exception $e) {
			echo $e->getMessage();
		}

		return $this;
	}

	public function to($location)
	{
		$this->redirectTo($location);
	}

	protected function id($message)
	{
		return isset($message['id']) ? $message['id'] : null;
	}

	public function getMessages()
	{
		return $this->hasMessages() ? $this->accessor->get() : false;	
	}

	public function hasMessages()
	{
		return $this->accessor->has();
	}
}