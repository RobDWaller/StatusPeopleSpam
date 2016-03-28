<?php namespace Helpers;

use Helpers\Server;

trait Ip
{
	public function ipAddress()
	{
		return $this->getServer()->REMOTE_ADDR;
	}
}