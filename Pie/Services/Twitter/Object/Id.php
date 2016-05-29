<?php namespace Services\Twitter\Object;

use Services\Object\AbstractObject;

class Id extends AbstractObject
{
	protected $id;

	public function __construct($id)
	{
		$this->id = $id;
	}
}