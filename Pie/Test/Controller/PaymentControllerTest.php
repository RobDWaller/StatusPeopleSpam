<?php namespace Test\Controller;

use Test\Controller\AbstractTestController;

class PaymentControllerTest extends AbstractTestController
{
	public function testViewPaymentPage()
	{
		$this->createTestAdmin();

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', 'login_email')
			->type('Test1234', 'login_password')
			->press('Login')
			->visit('/Dashboard/Home')
			->seePageIs('/Dashboard/Home')
			->see('Twitter Handle')
			->see('Recent Sign Ups')
			->see('Recent Blocks')
			->type('StatusPeople', 'handle')
			->press('Search')
			->see('Details Page')
			->see('StatusPeople');
	}

	public function testAddPayment()
	{
		$this->createTestAdmin();

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', 'login_email')
			->type('Test1234', 'login_password')
			->press('Login')
			->visit('/Account/User?id=1w90lGVIk9G15P')
			->see('Details Page')
			->see('StatusPeople')
			->select('type', '2')
			->press('Add Payment')
			->see('Details Page')
			->see('StatusPeople')
			->see('Purchase created successfully');
	}

	public function testViewProfile()
	{
		$this->createTestAdmin();

		$this->visit('/Admin/Login')
			->seePageIs('/Admin/Login')
			->type('test@test.com', 'login_email')
			->type('Test1234', 'login_password')
			->press('Login')
			->visit('/Account/User?id=89z7jGIzKq7QD')
			->see('Details Page')
			->see('RobDWaller')
			->click('View Account')
			->see('RobDWaller')
			->see('Fakers')
			->see('Blocked')
			->see('Search for friends or business rivals');
	}
}