<?php namespace Test\Controller;

use Test\Build;
use Model\Admin;
use Model\Login;
use Services\Authentication\Password;

abstract class AbstractTestController extends Build
{
	public function setUp()
	{
		parent::setUp();

		$this->dropTestAdmin();
		$this->dropLoginAttempts();
	}

	public function tearDown()
	{
		parent::tearDown();

		$this->dropTestAdmin();
		$this->dropLoginAttempts();
	}

	protected function createTestAdmin()
	{
		$password = new Password(PASSWORD_BCRYPT);
		
		$admin = new Admin();

		$admin->addAdmin('test@test.com', $password->generate('Test1234'));
	}

	protected function dropTestAdmin()
	{
		$admin = new Admin();

		$admin->deleteAdmin('test@test.com');
	}

	protected function dropLoginAttempts()
	{
		$login = new Login();

		$login->deleteLogin('::1');
	}
}