<?php namespace Helpers;

use Helpers\Server;

trait Ip
{
	use Server;

	public function ipAddress()
	{
		return $this->getServer()->REMOTE_ADDR;
	}
}