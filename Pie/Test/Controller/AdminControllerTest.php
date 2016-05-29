<?php namespace Test\Controllers;

use Test\Build;
use Fakers\ViewData;
use Services\Authentication\Auth;
use Services\Authentication\Password;
use Model\Admin;
use Model\Login;

class AdminControllerTest extends Build
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

	public function testLoginProcess()
	{
		$this->createTestAdmin();

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', 'login_email')
			->type('Test1234', 'login_password')
			->press('Login')
			->seePageIs('/Dashboard/Home');
	}

	public function testLoginProcessFail()
	{
		$this->createTestAdmin();

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', '#login_email')
			->type('asd', '#login_password')
			->press('Login')
			->seePageIs('/Admin/Login')
			->see('User Details Incorrect');
	}

	public function testIpBlock()
	{
		$this->createTestAdmin();

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', 'login_email')
			->type('Test1234', 'login_password')
			->press('Login')
			->seePageIs('/Dashboard/Home')
			->visit('/Admin/Logout')
			->see('Login');

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', '#login_email')
			->type('asd', '#login_password')
			->press('Login')
			->seePageIs('/Admin/Login')
			->see('User Details Incorrect');

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', '#login_email')
			->type('asd', '#login_password')
			->press('Login')
			->seePageIs('/Admin/Login')
			->see('User Details Incorrect');

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', '#login_email')
			->type('asd', '#login_password')
			->press('Login')
			->seePageIs('/Admin/Login')
			->see('User Details Incorrect');

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', '#login_email')
			->type('asd', '#login_password')
			->press('Login')
			->seePageIs('/Admin/Login')
			->see('User Details Incorrect');

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', '#login_email')
			->type('asd', '#login_password')
			->press('Login')
			->seePageIs('/Admin/Login')
			->see('User Details Incorrect');

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', '#login_email')
			->type('Test1234', '#login_password')
			->press('Login')
			->seePageIs('/Admin/Login')
			->see('User Details Incorrect');
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