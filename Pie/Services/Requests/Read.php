<?php namespace Services\Requests;

use Services\Object\Builder;

class Read
{
	public function __construct()
	{
		$this->obj = new Builder();
	}

	public function post()
	{
		return $this->obj->ArrayToObject($_POST);
	}

	public function get()
	{
		return $this->obj->ArrayToObject($_GET);
	}
}