<?php namespace Helpers;

use Helpers\Server;

trait Redirector
{
	use Server;

	public function getHost()
	{
		return $this->getServer()->HTTP_HOST;
	}

	public function redirectTo($location)
	{
		header('Location: http://' . $this->getHost() . $location);
		die();
	}
}