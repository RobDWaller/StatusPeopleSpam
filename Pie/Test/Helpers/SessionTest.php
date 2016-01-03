<?php namespace Test\Helpers;

use Test\Build;
use Helpers\Session;

class DirSession extends Build 
{
	use Session;

	public function testSession()
	{
		$this->setSession('test', 'FooBar');

		$this->assertTrue(isset($this->getSession()->test));
		$this->assertEquals('FooBar', $this->getSession()->test);

		$this->unsetSession('test');

		$this->assertFalse(isset($this->getSession()->test));
	}
}