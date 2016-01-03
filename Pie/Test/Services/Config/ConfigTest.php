<?php namespace Test\Services\Config;

use Test\Build;
use Services\Config\Loader;
use Services\Files\Facade;

class ConfigTest extends Build 
{
	public function testConfig()
	{
		$file = Facade::make($_SERVER['DOCUMENT_ROOT']."/Pie/Config/", 'foo', 'php');

		$file->create()->write("<?php \n\r return ['bar' => true];");

		$config = new Loader();

		$this->assertInstanceOf('Services\Config\Loader', $config);

		$this->assertTrue($config->get('foo.bar'));

		$this->assertFalse($config->get('bar.foo'));

		$file->delete();
	}
}