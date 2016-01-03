<?php namespace Test\Helpers;

use Test\Build;
use Helpers\Dir;

class DirTest extends Build 
{
	use Dir;

	public function testRootDir()
	{
		$this->assertTrue(is_string($this->getRootDirectory()));

		$this->assertStringStartsWith('/', $this->getRootDirectory());
	}

	public function testCurrentDir()
	{
		$this->assertTrue(is_string($this->getCurrentDirectory()));

		$this->assertStringStartsWith('/', $this->getCurrentDirectory());
	}
}