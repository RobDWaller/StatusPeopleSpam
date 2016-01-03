<?php namespace Services\Config;

use Services\Config\Loader;

class Facade 
{
	public static function make($url = null)
	{
		return new Loader($url);
	}
}