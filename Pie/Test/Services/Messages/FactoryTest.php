<?php namespace Test\Services\Messages;

use Test\Build;
use Services\Messages\Factory;

class FactoryTest extends Build
{

	public function testFactoryFailure()
	{
		$factory = new Factory('failure', ['hello world'], 'email');

		$this->assertInstanceOf('Services\Messages\Factory', $factory);
	}

	public function testFactoryAlert()
	{
		$factory = new Factory('alert', ['hello world'], 'email');

		$this->assertInstanceOf('Services\Messages\Factory', $factory);
	}

	public function testFactoryInfo()
	{
		$factory = new Factory('info', ['hello world'], 'email');

		$this->assertInstanceOf('Services\Messages\Factory', $factory);
	}

	public function testFactorySuccess()
	{
		$factory = new Factory('success', ['hello world'], 'email');

		$this->assertInstanceOf('Services\Messages\Factory', $factory);
	}

	public function testForceFactoryFail()
	{
		$this->setExpectedException(
          'InvalidArgumentException', 'Invalid Message Type blasdsad. Must be either alert, failure, success or info.'
        );

		$factory = new Factory('blasdsad', ['hello world'], 'email');
	}

	public function testGetters()
	{
		$factory = new Factory('success', ['hello world'], 'email');

		$this->assertEquals($factory->getType(), 'success');

		$this->assertContains('hello world', $factory->getMessages());

		$this->assertEquals($factory->getId(), 'email');
	}

	public function testHasId()
	{
		$factory = new Factory('success', ['hello world'], 'email');

		$this->assertTrue($factory->hasId());

		$factory2 = new Factory('success', ['hello world']);

		$this->assertFalse($factory2->hasId());
	}

}