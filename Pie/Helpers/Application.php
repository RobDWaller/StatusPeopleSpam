<?php namespace Helpers;

use Services\Config\Loader as Config;

trait Application
{
	public function isAppInTest()
	{
		$config = new Config;

		return gethostname() == $config->get('app.test');
	}
}