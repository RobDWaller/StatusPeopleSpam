<?php namespace Fakers\Score;

use Model\User;
use Model\UserInfo;
use Model\Cache;
use Services\Twitter\Twitter;
use Helpers\FakersScore;
use Model\Checker;
use Model\Check;
use Model\SpamScore;
use Model\CheckScore;

class Generic
{
	use FakersScore;

	protected $checker;

	protected $check;

	protected $user;

	protected $userInfo;

	protected $cache;

	protected $spamScore;

	protected $checkScore;

	protected $twitter;

	public function __construct(User $user, 
		UserInfo $userInfo, 
		Cache $cache, 
		Checker $checker,
		Check $check, 
		SpamScore $spamScore,
		CheckScore $checkScore,
		Twitter $twitter)
	{
		$this->user = $user;

		$this->userInfo = $userInfo;

		$this->twitter = $twitter;

		$this->cache = $cache;

		$this->checker = $checker;

		$this->check = $check;

		$this->spamScore = $spamScore;

		$this->checkScore = $checkScore;
	}

	public function getCheckers()
	{
		return $this->checker->getCheckers(0, 20, strtotime('-1 Month'));
	}

	public function updateCheckerTime($userId, $time)
	{
		return $this->checker->updateCheckerTime($userId, $time);
	}

	public function getUsersToCheck($users, $time)
	{
		return $this->check->getUsersToCheck($users, $time);
	}

	public function updateLastCheckTime($twitterId, $screenName)
	{	
		return $this->check->updateLastCheckTime($twitterId, $screenName, time());
	}

	public function getTwitterDetails($twitterId)
	{
		return $this->user->getTwitterDetails($twitterId);
	}

	public function getTwitterBio($user) 
	{
		$details = $this->getTwitterDetails($user->userid);

		return $this->twitter->getBio($details->token, $details->secret, $user->twitterid);
	}

	public function updateInfoRecord($bio)
	{
		$infoRecords = $this->userInfo->findTwitterId($bio->twitterId);

		if ($infoRecords->count() >= 1) {
			$this->userInfo->updateUserInfo($bio->twitterId, $bio->screenName, $bio->avatar);
		}
	}

	public function updateStatsData($userId, $languageData, $statsData, $spamData, $time)
	{
		$languages = $this->convertLanguageDataOldFormat($languageData);
		$stats = $this->convertStatsDataOldFormat($statsData);
		$spam = $this->convertSpamDataOldFormat($spamData);

		$cacheCheck = $this->cache->getCache($userId);

		if ($cacheCheck->count >= 1) {

			if ($cacheCheck->first()->created < strtotime('-15 Days')) {
				return $this->cache->updateCache($userId, $languages, $stats, $spam, $time);
			}

			return false;
		}

		return $this->cache->addCache($userId, $languages, $stats, $spam, $time);
	}

	public function updateSpamScore($twitterId, $spamCount, $inactiveCount, $checksCount, $followerCount, $time)
	{
		return $this->spamScore->updateSpamDetails(
			$twitterId, 
			$spamCount, 
			$inactiveCount, 
			$checksCount, 
			$followerCount, 
			$time
		);
	}

	public function addCheckScore(
		$twitterId, 
		$screenName, 
		$spamCount, 
		$inactiveCount, 
		$checksCount, 
		$followerCount, 
		$time
	) {
		return $this->checkScore->addCheckScore(
			$twitterId, 
			$screenName, 
			$spamCount, 
			$inactiveCount, 
			$checksCount, 
			$followerCount, 
			$time
		);
	}

	public function updateCheckTime($twitterId, $screenName)
	{
		$this->check->updateUsersToCheckTime($twitterId, $screenName, time());
	}

	public function addFakeFollowers($userId, $spamData)
	{
		$insertString = $this->buildSpamInsertString($userId, $spamData);

		return $this->fake->addFakes($insertString);
	}
}