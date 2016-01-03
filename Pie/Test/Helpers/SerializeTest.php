<?php namespace Test\Helpers;

use Test\Build;
use Helpers\Serialize;

class SerializeTest extends Build 
{
	use Serialize;

	public function testSerializeUnserialize()
	{
		$string = 'Foo Bar';

		$newString = $this->serialize($string);

		$this->assertTrue($string !== $newString);
		$this->assertFalse($string === $newString);

		$oldString = $this->unserialize($newString);

		$this->assertEquals($string, $oldString);
	}
}