<?php namespace Services\Database\Object;

use Services\Object\AbstractObject;

class Parameter extends AbstractObject
{
	protected $name;

	protected $value;

	protected $type;

	protected $length;

	public function __construct($name, $value, $type, $length)
	{
		$this->name = $name;

		$this->value = $value;

		$this->type = $type;

		$this->length = $length;
	}
}