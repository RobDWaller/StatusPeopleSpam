<?php namespace Services\Encryption;

use Hashids\Hashids;

class Encrypt
{
	protected $cryptor;

	public function __construct()
	{
		$this->cryptor = new Hashids('aWdRgYjIl13579pM');
	}

	public function crypt($string)
	{
		return $this->cryptor->encode($string);
	}

	public function decrypt($string)
	{
		return $this->cryptor->decode($string);
	}
}