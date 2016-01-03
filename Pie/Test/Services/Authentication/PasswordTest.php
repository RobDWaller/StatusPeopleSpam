<?php namespace Test\Authentication;

use Test\Build;
use Services\Authentication\Password;

class PasswordTest extends Build 
{
	public function testPassword()
	{
		$password = new Password(PASSWORD_BCRYPT);

		$hash = $password->generate('p@ssw0rd');

		$this->assertNotEquals('p@ssw0rd', $hash);

		$this->assertTrue($password->verify('p@ssw0rd', $hash));

		$this->assertFalse($password->verify('psswrd', $hash));
	}
}