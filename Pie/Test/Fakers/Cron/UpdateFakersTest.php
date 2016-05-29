<?php namespace Test\Fakers\Cron;

use Test\Build;
use Fakers\Cron\UpdateFakersCheck;
use Model\Checker;
use Model\Check;
use Model\User;
use Services\Twitter\Twitter;
use Model\UserInfo;
use Model\Cache;
use Fakers\Score\Generic;
use Fakers\Score\FollowerIdProcessor;
use Fakers\Score\ChecksProcessor;
use Fakers\Score\Follower;
use Fakers\Score\Calculator;
use Fakers\Score\Score;
use Fakers\Score\Language;
use Fakers\Score\Stats;
use Model\SpamScore;
use Model\CheckScore;

class UpdateFakersTest extends Build
{
	public function testUpdateFakersCheck()
	{
		$checker = new Checker;
		$check = new Check;
		$user = new User;
		$twitter = new Twitter;
		$userInfo = new UserInfo;
		$cache = new Cache;
		$spamScore = new SpamScore;
		$checkScore = new CheckScore;

		$generic = new Generic($user, $userInfo, $cache, $checker, $check, $spamScore, $checkScore, $twitter);
		$processor = new FollowerIdProcessor($twitter);
		$checks = new ChecksProcessor($twitter);
		$follower = new Follower($twitter);
		$calculator = new Calculator;
		$language = new Language;
		$stats = new Stats;

		$score = new Score($calculator, $checks, $follower, $processor, $generic);

		$check = new UpdateFakersCheck($generic, $score, $language, $stats);

		$result = $check->run();

		$this->assertInstanceOf('Fakers\Cron\Object\CronComplete', $result);

		$this->assertInstanceOf('Fakers\Cron\Object\Errors', $result->errors);

		$this->assertInstanceOf('Fakers\Cron\Object\Results', $result->results);

		if ($result->errors->count() >= 1) {
			$this->assertInstanceOf('Fakers\Cron\Object\Error', $result->errors->first());
		}

		if ($result->results->count() >= 1) {
			$this->assertInstanceOf('Fakers\Cron\Object\Result', $result->results->first());

			$cacheResult = $cache->getCache($result->results->first()->result->twitterId);

			$this->assertEquals($result->results->first()->result->timestamp, $cacheResult->first()->created);

			$checkScoreResult = $checkScore->getScore($result->results->first()->result->twitterId, 1); 

			$this->assertEquals($result->results->first()->result->twitterId, $checkScoreResult->first()->twitterid);

			$this->assertEquals($result->results->first()->result->fakes, $checkScoreResult->first()->fakes);

			$this->assertEquals($result->results->first()->result->inactive, $checkScoreResult->first()->potential);

			$this->assertEquals($result->results->first()->result->checks, $checkScoreResult->first()->checks);

			$this->assertEquals($result->results->first()->result->timestamp, $checkScoreResult->first()->created);

			$spamScoreResult = $spamScore->getScore($result->results->first()->result->twitterId);

			$this->assertEquals($result->results->first()->result->twitterId, $spamScoreResult->first()->twitterid);

			$this->assertEquals($result->results->first()->result->fakes, $spamScoreResult->first()->fakes);

			$this->assertEquals($result->results->first()->result->inactive, $spamScoreResult->first()->potential);

			$this->assertEquals($result->results->first()->result->checks, $spamScoreResult->first()->checks);

			$this->assertEquals($result->results->first()->result->timestamp, $spamScoreResult->first()->created);
		}
	} 
}