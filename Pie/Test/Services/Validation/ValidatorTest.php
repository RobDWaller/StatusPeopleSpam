<?php namespace Test\Services\Validation;

use Test\Build;
use Services\Validation\Validator;
use Services\Authentication\Facade as Authentication;

class ValidatorTest extends Build
{
	public function testValidation()
	{
		$v = new Validator();

		$this->assertInstanceOf('Services\Validation\Validator', $v);

		$result = $v->string('name', 'foo bar')->email('email', 'foo@bar.com');

		$this->assertTrue($result->check()->isValid());
		$this->assertFalse($result->getErrors());
	}

	public function testStringFailure()
	{
		Authentication::auth()->login(1, 1, 1);

		$v = new Validator();

		$result = $v->string('name', 1234);

		$this->assertFalse($result->check()->isValid());

		$errors = $result->getErrors();

		$this->assertContains('Please enter a valid name', $errors->current()->getMessages());

		$this->assertGreaterThanOrEqual(1, $result->setMessages());
		
		$this->assertContains('Please enter a valid name', $result->getMessages()->current()->getMessages());
	}

	public function testEmailFailure()
	{
		$v = new Validator();

		$result = $v->email('email', 'foo.bar.com');

		$this->assertFalse($result->check()->isValid());

		$errors = $result->getErrors();

		$this->assertContains('Please enter a valid email', $errors->current()->getMessages());
	}

	public function testForceFail()
	{
		Authentication::auth()->login(1, 1, 1);

		$v = new Validator();

		$v->setMessage('foo');
    	
    	$this->assertGreaterThanOrEqual(1, $v->setMessages());

    	$this->assertContains('Please enter a valid foo', $v->getMessages()->current()->getMessages());
	}

	public function testWithoutAuthentication()
	{
		$v = new Validator();

		$v->setMessage('bar');
    	
    	$this->assertGreaterThanOrEqual(1, $v->setMessages());

    	$this->assertContains('Please enter a valid bar', $v->getMessages()->current()->getMessages());
	}
}