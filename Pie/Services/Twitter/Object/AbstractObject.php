<?php namespace Services\Twitter\Object;

use Exception\TwitterException;

abstract class AbstractObject
{
	public function __get($name)
	{
		if (property_exists($this, $name)) {
			return $this->$name;
		}

		throw new TwitterException('Class: This property [' . $name . '] does not exist');
	}
}