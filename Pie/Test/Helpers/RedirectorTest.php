<?php namespace Test\Helpers;

use Test\Build;
use Helpers\Redirector;

class RedirectorTest extends Build 
{
	use Redirector;

	public function testRedirector()
	{
		$_SERVER['HTTP_HOST'] = 'fakers.com';

		$this->assertTrue(is_string($this->getHost()));
		$this->assertEquals('fakers.com', $this->getHost());
	}
}