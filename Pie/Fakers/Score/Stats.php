<?php namespace Fakers\Score;

use Fakers\Score\Object\Stats as StatsObject;
use Fakers\Score\Object\UserGroup;
use Exception\FakerScoreException;

class Stats
{
	protected $count;

	protected $tweetsPerDay;

	protected $followers;

	protected $follows;

	protected $lastTweetOne;

	protected $lastTweetThirty;

	protected $lastTweetHundred;

	protected $follows250;

	protected $follows500;

	protected $follows1000;

	protected $followers250;

	protected $followers500;

	protected $followers1000;

	public function getStats(UserGroup $followerGroup)
	{
		$this->clearCounts();

		if ($followerGroup->count() >= 1) {
			foreach ($followerGroup as $followers) {
				foreach ($followers as $follower) {
					
					$this->generalStats($follower);

					$this->lastTweet($follower);

					$this->followers($follower);

					$this->follows($follower);
				}
			}

			return new StatsObject(
				$this->count,
				$this->tweetsPerDay,
				$this->followers,
				$this->follows,
				$this->lastTweetOne,
				$this->lastTweetThirty,
				$this->lastTweetHundred,
				$this->followers250,
				$this->followers500,
				$this->followers1000,
				$this->follows250,
				$this->follows500,
				$this->follows1000
			);
		}

		throw new FakerScoreException('Unable to create faker stats');
	}	

	protected function clearCounts()
	{
		$this->count = 0;

		$this->tweetsPerDay = 0;
			
		$this->followers = 0;

		$this->follows = 0;

		$this->lastTweetOne = 0;

		$this->lastTweetThirty = 0;
		
		$this->lastTweetHundred = 0;
		
		$this->followers250 = 0;
		
		$this->followers500 = 0;
		
		$this->followers1000 = 0;
		
		$this->follows250 = 0;
		
		$this->follows500 = 0;
		
		$this->follows1000 = 0;
	}

	protected function generalStats($follower)
	{
		$this->count += 1;
		$this->tweetsPerDay += $follower->tweetsPerDay;
		$this->followers += $follower->followers;
		$this->follows += $follower->follows;
	}

	protected function lastTweet($follower)
	{
		if ($follower->lastTweet->createdDays >= 1 && $follower->lastTweet->createdDays < 30) {
			$this->lastTweetOne += 1;
		}
		
		if ($follower->lastTweet->createdDays >= 30 && $follower->lastTweet->createdDays < 100) {
			$this->lastTweetThirty += 1;
		}
		
		if ($follower->lastTweet->createdDays >= 100) {
			$this->lastTweetHundred += 1;
		}
	}

	protected function followers($follower)
	{
		if ($follower->followers < 250) {
			$this->followers250 += 1;
		}

		if ($follower->followers >= 250 && $follower->followers < 1000) {
			$this->followers500 += 1;
		}

		if ($follower->followers >= 1000) {
			$this->followers1000 += 1;
		}
	}

	protected function follows($follower)
	{
		if ($follower->follows < 250) {
			$this->follows250 += 1;
		}

		if ($follower->follows >= 250 && $follower->follows < 1000) {
			$this->follows500 += 1;
		}

		if ($follower->follows >= 1000) {
			$this->follows1000 += 1;
		}
	}
}