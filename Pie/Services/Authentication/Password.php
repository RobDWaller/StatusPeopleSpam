<?php namespace Services\Authentication;

class Password
{
	protected $type;

	public function __construct($type = PASSWORD_DEFAULT)
	{
		$this->type = $type;
	}

	public function generate($password)
	{
		return password_hash($password, $this->type);
	}

	public function verify($password, $hash)
	{
		return password_verify($password, $hash);
	}

}