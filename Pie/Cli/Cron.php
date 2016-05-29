<?php namespace Cli;

use Fakers\Cron\UpdateFakersCheck;
use Model\Checker;
use Model\Check;

class Cron
{
	protected $cron;

	public function updateFakersCheck()
	{
		$cronJob = new UpdateFakersCheck(
			new Checker,
			new Check
		);

		$cronJob->run();
	}
}