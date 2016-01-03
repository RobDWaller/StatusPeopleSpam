<?php namespace Test\Controllers;

use Test\Build;
use Fakers\ViewData;
use Services\Authentication\Auth;

class AdminControllerTest extends Build
{
	public function testAdminLogin()
	{
		$this->visit('/Admin/Login')
			->see('Email')
			->see('Password');
	}

	public function testLoginProcess()
	{
		$this->visit('/Admin/Login')
			->type('rob@statuspeople.com', 'email')
			->type('Palumbo123', 'password')
			->press('Login')
			->seePageIs('/Dashboard/Home');
	}

	public function testLoginProcessFail()
	{
		$this->visit('/Admin/Login')
			->type('rob@statuspeople.com', 'email')
			->type('asd', 'password')
			->press('Login')
			->seePageIs('/Admin/Login');
	}
}