<?php namespace Services\Requests;

use Services\Object\Builder;

class Server
{
	protected $object;

	public function __construct()
	{
		$this->object = new Builder;
	}

	public function get($key)
	{
		return isset($_SERVER[$key]) ? $_SERVER[$key] : false;
	}

	public function all()
	{
		return $this->object->ArrayToObject($_SERVER);
	}
} 
