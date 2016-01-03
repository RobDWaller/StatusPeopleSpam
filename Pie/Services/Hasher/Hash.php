<?php namespace Services\Hasher;

use Hashids\Hashids;

class Hash
{
	protected $hasher;

	public function __construct()
	{
		$this->hasher = new Hashids();
	}

	public function encode($id)
	{
		return $this->hasher->encode($id, time());
	}

	public function decode($key)
	{
		$decode = $this->hasher->decode($key);

		return isset($decode[0]) && !empty($decode[0]) ? $decode[0] : null;
	}
}