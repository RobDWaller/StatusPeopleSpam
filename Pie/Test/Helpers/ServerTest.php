<?php namespace Test\Helpers;

use Test\Build;
use Helpers\Server;

class ServerTest extends Build 
{
	use Server;

	public function testServer()
	{
		$this->assertEquals($_SERVER['DOCUMENT_ROOT'], $this->getServer()->DOCUMENT_ROOT);
	}
}