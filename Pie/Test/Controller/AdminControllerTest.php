<?php namespace Test\Controller;

use Test\Build;
use Fakers\ViewData;
use Services\Authentication\Auth;
use Test\Controller\AbstractTestController;

class AdminControllerTest extends AbstractTestController
{
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
}