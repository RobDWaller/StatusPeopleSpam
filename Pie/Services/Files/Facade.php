<?php namespace Services\Files;

use Services\Files\Loader as Files;

class Facade
{
	public static function make($directory, $file = null, $type = null)
	{
		return new Files($directory, $file, $type);
	}
}