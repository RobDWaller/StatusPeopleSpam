<?php namespace Services\Object;

use Exception\ObjectException;

abstract class AbstractObject
{
	public function __get($name)
	{
		if (property_exists($this, $name)) {
			return $this->$name;
		}

		throw new ObjectException('Class: This property [' . $name . '] does not exist');
	}
}