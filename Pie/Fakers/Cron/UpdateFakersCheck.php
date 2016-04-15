<?php namespace Fakers\Cron;

use Model\Checker;
use Model\Check;
use Model\User;
use Services\Twitter\Twitter;

class UpdateFakersCheck implements CronInterface
{
	protected $checker;

	protected $check;

	protected $user;

	protected $twitter;

	protected $spam = [];

	public function __construct(Checker $checker, Check $check, User $user, Twitter $twitter)
	{
		$this->checker = $checker;

		$this->check = $check;

		$this->user = $user;

		$this->twitter = $twitter;
	}

	public function run()
	{
		$users = $this->checker->getCheckers(0, 20, strtotime('-1 Month'));

		if ($users->count() >= 1) {

			$this->processUserChecks($users);

			$this->updateCheckerTime($users);
		}
	}

	protected function updateCheckerTime($users)
	{
		foreach ($users as $k => $u) {
			$this->checker->updateCheckerTime($u->userid, time());
		}
	}

	public function processUserChecks($users)
	{
		$userRecords = $this->check->getUsersToCheck($users);

		if ($userRecords->count() >= 1) {

			foreach ($userRecords as $k => $u) {
				$this->check->updateLastCheckTime($u->twitterid, $u->screen_name, time());

				$bio = $this->getTwitterBio($u);
			}
		}
	}

	public function getTwitterBio($user) 
	{
		$details = $this->user->getTwitterDetails($u->twitterid);

		return $this->twitter->getBio();
	}
}