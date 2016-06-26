<?php namespace Test\Helpers;

use Test\Build;
use Helpers\Cli;

class CliTest extends Build
{
	use Cli;

	public function testHasArguments()
	{
		$result = $this->hasArguments(['fileName', 'class1', 'method2']);

		$this->assertTrue($result);
	}

	public function testHasArgumentsFail()
	{
		$result = $this->hasArguments([]);

		$this->assertFalse($result);
	}

	public function testHasArgumentsFailTwo()
	{
		$result = $this->hasArguments(['onlyFileName']);

		$this->assertFalse($result);
	}

	public function testHasArgumentsFailThree()
	{
		$result = $this->hasArguments(['onlyFileName', 'andClass']);

		$this->assertFalse($result);
	}
}