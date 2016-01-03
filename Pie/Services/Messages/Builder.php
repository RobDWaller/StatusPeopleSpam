<?php namespace Services\Messages;

use Services\Messages\Collection;
use Services\Files\Facade as File;
use Services\Messages\Accessor;
use Helpers\Session;
use Hashids\Hashids;
use Helpers\Serialize;
use Helpers\Dir;

class Builder
{
	use Serialize;
	use Dir;
	use Session;

	protected $messages;
	protected $files;
	protected $hash;
	protected $accessor;

	public function __construct(Collection $messages)
	{
		$this->messages = $messages;
		$this->hash = new Hashids();
		$this->accessor = new Accessor();
	}

	public function set($key)
	{
		$hash = $this->hash->encode($key, time());

		$file = File::make($this->accessor->messagesDirectory(), $hash, 'txt');

		$string = $this->serialize($this->messages);

		$this->setSession('messages', $hash);

		return $file->create()->write($string);
	}

	public function get()
	{
		return $this->accessor->get();
	}
} 