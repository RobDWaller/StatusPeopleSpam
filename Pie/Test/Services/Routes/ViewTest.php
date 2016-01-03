<?php namespace Test\Services\Routes;

use Test\Build;
use Services\Routes\View;
use Services\Files\Facade;

class ViewTest extends Build
{
	public function testView()
	{
		$view = new View();

		$this->assertInstanceOf('Services\Routes\View', $view);
	}

	public function testAddData()
	{
		$view = new View();

		$view->addData('foo', 'bar');

		$this->assertContains('bar', $view->getData());	
	}

	public function testFilePath()
	{
		$view = new View();

		$view->setFile('foo.php');

		$this->assertStringEndsWith('foo.php', $view->filePath());

		$file = Facade::make('/var/www/spam/Pie/Crust');

		$this->assertTrue($file->directoryExists());
	}

	public function testGet()
	{
		$_GET['foo'] = 'bar';

		$view = new View();

		$this->assertEquals('bar', $view->get()->foo);
	}

	public function testPost()
	{
		$_POST['foo'] = 'bar';

		$view = new View();

		$this->assertEquals('bar', $view->post()->foo);
	}

}