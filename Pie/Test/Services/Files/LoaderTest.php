<?php namespace Test\Services\Files;

use Test\Build;
use Services\Files\Loader;

class LoaderTest extends Build
{
	public function testClass()
	{
		$file = new Loader($_SERVER['DOCUMENT_ROOT']."/Pie/Storage/Test/");

		$this->assertInstanceOf('Services\Files\Loader', $file);
	}

	public function testMakeAndDeleteFile()
	{
		$file = new Loader($_SERVER['DOCUMENT_ROOT']."/Pie/Storage/Test/", "bar", "txt");

		if ($file->exists()) {
			$this->assertTrue($file->delete());
		}

		$result = $file->create();

		$this->assertInstanceOf('Services\Files\Loader', $result);

		$this->assertTrue($file->exists());

		$this->assertTrue($result->delete());
	}

	public function testWriteAndReadFile()
	{
		$file = new Loader($_SERVER['DOCUMENT_ROOT']."/Pie/Storage/Test/", "bar", "txt");
		
		$this->assertGreaterThan(1, $file->create()->write('Hello World'));

		$this->assertEquals('Hello World', $file->read(200));

		$this->assertTrue($file->delete());
	}

	public function testMakeDestroyDirectory()
	{
		$file = new Loader($_SERVER['DOCUMENT_ROOT']."/Pie/Storage/Test/", "bar", "txt");

		$file->deleteDirectory();

		$this->assertTrue($file->makeDirectory());

		$this->assertTrue($file->deleteDirectory());
	}
}