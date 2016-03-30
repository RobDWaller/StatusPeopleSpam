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

			foreach ($users as $k => $u) {
				$this->checker->updateCheckerTime($u->userid, time());

				$this->processUserChecks($u);
			}
		}
	}

	protected function processUserChecks($user)
	{
		$userRecords = $this->check->getUserToCheck($user->userid);

		if ($userRecords->count() >= 1) {

			foreach ($userRecords as $k => $u) {
				$this->check->updateLastCheckTime($u->twitterid, $u->screen_name, time());

				$bio = $this->getTwitterBio($u);
			}
		}
	}

	protected function getTwitterBio($user) 
	{
		$details = $this->getTwitterDetails($u->twitterid);

		return $this->twitter->getBio();
	}
}