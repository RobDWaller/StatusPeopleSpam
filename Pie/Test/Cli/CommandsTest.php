<?php namespace Test\Cli;

use Test\Build;
use Cli\Commands;

class CommandsTest extends Build
{
	public function testCommands()
	{
		$commands = new Commands(['fileName', 'class1', 'method2']);

		$this->assertInstanceOf('Cli\Commands', $commands);
	}

	public function testCronUpdateFakersCheck()
	{
		$commands = new Commands(['init.php', 'Cli\Cron', 'updateFakersCheck']);

		$result = $commands->make();

		$this->assertInstanceOf();
	}
}