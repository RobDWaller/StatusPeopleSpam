<?php namespace Test\Services\Files;

use Test\Build;
use Services\Files\Facade;

class FacadeTest extends Build 
{
	public function testFacade()
	{
		$file = Facade::make($_SERVER['DOCUMENT_ROOT']."/Pie/Storage/Test/", 'bar', 'txt');

		$this->assertInstanceOf('Services\Files\Loader', $file);

		if ($file->exists()) {
			$this->assertTrue($file->delete());
		}

		$result = $file->create();

		$this->assertInstanceOf('Services\Files\Loader', $result);

		$this->assertTrue($file->exists());

		$this->assertTrue($result->delete());
	}	
}