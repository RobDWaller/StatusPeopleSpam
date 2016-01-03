<?php namespace Services\Messages;

use Services\Messages\Factory;

class Facade
{
	public static function make($type, array $messages, $id = false)
	{
		return new Factory($type, $messages, $id);
	}
}