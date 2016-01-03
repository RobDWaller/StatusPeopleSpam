<?php namespace Test\Authentication;

use Test\Build;
use Services\Authentication\Auth;

class AuthTest extends Build 
{
	public function testAuth()
	{
		$auth = new Auth();

		$this->assertInstanceOf('Services\Authentication\Auth', $auth);

		$auth->login(2, 3, 4);

		$this->assertEquals(2, $auth->id());

		$this->assertEquals(3, $auth->user()->primaryid);

		$this->assertEquals(4, $auth->user()->type);
	}
}