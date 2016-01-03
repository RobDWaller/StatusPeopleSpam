<?php namespace Test\Services\Messages;

use Test\Build;
use Services\Messages\Builder as Messages;
use Services\Messages\Facade;
use Services\Messages\Collection;
use Helpers\Session;
use Services\Authentication\Auth;

class BuilderTest extends Build
{
	use Session;

	public function testBuilder()
	{
		$auth = new Auth();

		$auth->login(1, 1, 1);

		$messages[] = Facade::make('alert', ['Hello'], 'email');
		$messages[] = Facade::make('success', ['World'], 'password');

		$collection = new Collection($messages);

		$messages = new Messages($collection);

		$this->assertInstanceOf('Services\Messages\Builder', $messages);

		$result = $messages->set($auth->id());

		$this->assertGreaterThanOrEqual(1, $result);

		$this->assertTrue(isset($this->getSession()->messages));
		$this->assertTrue(is_string($this->getSession()->messages));

		$getResult = $messages->get(); 

		$this->assertInstanceOf('Services\Messages\Collection', $getResult);

		$this->assertContains('Hello', $getResult->current()->getMessages());
		$this->assertContains('World', $getResult->next()->getMessages());
	}
}