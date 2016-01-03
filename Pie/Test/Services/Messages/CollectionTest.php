<?php namespace Test\Services\Messages;

use Test\Build;
use Services\Messages\Facade;
use Services\Messages\Collection;

class CollectionTest extends Build
{
	public function testCollection()
	{
		$messages[] = Facade::make('alert', ['Hello'], 'email');
		$messages[] = Facade::make('success', ['World'], 'password');
		$messages[] = Facade::make('failure', ['How']);
		$messages[] = Facade::make('info', ['Are'], 'lastname');

		$collection = new Collection($messages);

		$this->assertInstanceOf('Services\Messages\Collection', $collection);

		$this->assertEquals($collection->count(), 4);

		$collection->next();
		$collection->next();

		$this->assertFalse($collection->current()->hasId());

		$collection->rewind();

		$this->assertContains('Hello', $collection->current()->getMessages());
	}
}