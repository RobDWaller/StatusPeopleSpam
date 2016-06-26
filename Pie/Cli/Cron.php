<?php namespace Cli;

use Fakers\Cron\UpdateFakersCheck;
use Fakers\Score\Generic;
use Fakers\Score\Score;
use Fakers\Score\Language;
use Fakers\Score\Stats;
use Model\User;
use Model\UserInfo;
use Model\Cache;
use Model\Checker;
use Model\Check;
use Model\SpamScore;
use Model\CheckScore;
use Services\Twitter\Twitter;
use Fakers\Score\Calculator;

class Cron
{
	protected $cron;

	public function updateFakersCheck()
	{
		$generic = new Generic(
			new User, 
			new UserInfo, 
			new Cache, 
			new Checker, 
			new Check, 
			new SpamScore, 
			new CheckScore,
			new Twitter
		);

		$score = new Score(new Calculator);
		$language = new Language();
		$stats = new Stats();  

		$cronJob = new UpdateFakersCheck(
			$generic,
			$score,
			$language,
			$stats
		);

		$cronJob->run();
	}
}