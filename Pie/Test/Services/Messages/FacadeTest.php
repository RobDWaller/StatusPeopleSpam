<?php namespace Test\Services\Messages;

use Test\Build;
use Services\Messages\Facade;

class FacadeTest extends Build
{
	public function testFacade()
	{
		$factory = Facade::make('failure', ['hello world'], 'email');

		$this->assertInstanceOf('Services\Messages\Factory', $factory);

		$factory2 = Facade::make('failure', ['hello world']);

		$this->assertInstanceOf('Services\Messages\Factory', $factory2);

		$this->assertFalse($factory2->hasId());
	}
}