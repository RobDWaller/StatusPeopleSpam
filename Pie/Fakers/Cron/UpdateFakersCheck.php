<?php namespace Fakers\Cron;

use Fakers\Score\Generic;
use Fakers\Score\Score;
use Fakers\Score\Language;
use Fakers\Score\Stats;
use Exception;
use Fakers\Cron\Object\Error;
use Fakers\Cron\Object\Errors;
use Fakers\Cron\Object\Results;
use Fakers\Cron\Object\Result;
use Fakers\Cron\Object\CronComplete;

class UpdateFakersCheck implements CronInterface
{
	protected $results = [];

	protected $errors = [];

	protected $scoreGeneric;

	protected $score;

	protected $language;

	protected $stats;

	protected $spam = [];

	public function __construct(Generic $scoreGeneric, Score $score, Language $language, Stats $stats)
	{
		$this->scoreGeneric = $scoreGeneric;

		$this->score = $score;

		$this->language = $language;

		$this->stats = $stats;
	}

	public function run()
	{
		$users = $this->scoreGeneric->getCheckers();

		if ($users->count() >= 1) {

			$this->updateCheckerTime($users);

			return $this->processUserChecks($users);
		}
	}

	protected function updateCheckerTime($users)
	{
		foreach ($users as $k => $u) {
			$this->scoreGeneric->updateCheckerTime($u->userid, time());
		}
	}

	public function processUserChecks($users)
	{
		$userRecords = $this->scoreGeneric->getUsersToCheck($users, strtotime('-12 hours'));
		
		if ($userRecords->count() >= 1) {

			foreach ($userRecords as $k => $u) {
				
				$this->scoreGeneric->updateLastCheckTime($u->twitterid, $u->screen_name);

				$bio = $this->scoreGeneric->getTwitterBio($u);

				$this->scoreGeneric->updateInfoRecord($bio);

				try {
					
					$score = $this->score->getFakerScore($bio, $u);

					$languageStats = $this->language->getLanguageStats($this->score->getFollowers());

					$stats = $this->stats->getStats($this->score->getFollowers());

					$this->scoreGeneric->updateStatsData(
						$score->twitterId, 
						$languageStats, 
						$stats, 
						$score->fakeFollowers,
						$score->timestamp
					);

					$this->scoreGeneric->updateSpamScore(
						$score->twitterId, 
						$score->fakes, 
						$score->inactive, 
						$score->checks, 
						$score->followers,
						$score->timestamp
					);

					$this->scoreGeneric->addCheckScore(
						$score->twitterId, 
						$score->screenName,
						$score->fakes, 
						$score->inactive, 
						$score->checks, 
						$score->followers,
						$score->timestamp			
					);

					$this->scoreGeneric->updateCheckTime($score->twitterId, $score->screenName);

					$this->results[] = new Result($score);

				} catch (Exception $e) {

					$this->errors[] = new Error($e->getMessage, $e->getLine(), $e->getFile());
				}
			}
		}

		return new CronComplete(new Results($this->results), new Errors($this->errors));
	}
}