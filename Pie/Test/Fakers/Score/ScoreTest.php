<?php namespace Test\Fakers\Score;

use Test\Build;
use Fakers\Score\Score;
use Fakers\Score\Calculator;
use Fakers\Score\ChecksProcessor;
use Fakers\Score\Follower;
use Fakers\Score\FollowerIdProcessor;
use Fakers\Score\Generic;
use Services\Twitter\Twitter;
use Model\User;
use Model\UserInfo;
use Model\Cache;
use Model\Checker;
use Model\Check;
use Model\CheckScore;
use Model\SpamScore;

class ScoreTest extends Build
{
	public function testGetFakersScoreSmall()
	{
		$twitter = new Twitter;

		$calculator = new Calculator;
		$checksProcessor = new ChecksProcessor($twitter);
		$follower = new Follower($twitter);
		$followerIdProcessor = new FollowerIdProcessor($twitter);

		$user = new User();
		$userInfo = new UserInfo();
		$cache = new Cache;
		$checker = new Checker;
		$check = new Check;
		$checkScore = new CheckScore;
		$spamScore = new SpamScore;
		$generic = new Generic($user, $userInfo, $cache, $checker, $check, $spamScore, $checkScore, $twitter);

 		$score = new Score($calculator, $checksProcessor, $follower, $followerIdProcessor, $generic);

 		$this->assertInstanceOf('Fakers\Score\Score', $score);

 		$user = new TestUser(198192466, 518048523);

 		$bio = $generic->getTwitterBio($user);

 		$result = $score->getFakerScore($bio, $user);

 		$this->assertInstanceOf('Fakers\Score\Object\Score', $result);

 		$this->assertEquals($result->followers, $result->checks);

 		sleep(30);
	}

	public function testGetFakersScoreMedium()
	{
		$twitter = new Twitter;

		$calculator = new Calculator;
		$checksProcessor = new ChecksProcessor($twitter);
		$follower = new Follower($twitter);
		$followerIdProcessor = new FollowerIdProcessor($twitter);

		$user = new User();
		$userInfo = new UserInfo();
		$cache = new Cache;
		$checker = new Checker;
		$check = new Check;
		$checkScore = new CheckScore;
		$spamScore = new SpamScore;
		$generic = new Generic($user, $userInfo, $cache, $checker, $check, $spamScore, $checkScore, $twitter);

 		$score = new Score($calculator, $checksProcessor, $follower, $followerIdProcessor, $generic);

 		$this->assertInstanceOf('Fakers\Score\Score', $score);

 		$user = new TestUser(31386162, 198192466);

 		$bio = $generic->getTwitterBio($user);

 		$result = $score->getFakerScore($bio, $user);

 		$this->assertInstanceOf('Fakers\Score\Object\Score', $result);

 		$this->assertEquals(500, $result->checks);

 		sleep(30);
	}

	public function testGetFakersScoreLarge()
	{
		$twitter = new Twitter;

		$calculator = new Calculator;
		$checksProcessor = new ChecksProcessor($twitter);
		$follower = new Follower($twitter);
		$followerIdProcessor = new FollowerIdProcessor($twitter);

		$user = new User();
		$userInfo = new UserInfo();
		$cache = new Cache;
		$checker = new Checker;
		$check = new Check;
		$checkScore = new CheckScore;
		$spamScore = new SpamScore;
		$generic = new Generic($user, $userInfo, $cache, $checker, $check, $spamScore, $checkScore, $twitter);

 		$score = new Score($calculator, $checksProcessor, $follower, $followerIdProcessor, $generic);

 		$this->assertInstanceOf('Fakers\Score\Score', $score);

 		$user = new TestUser(1919216960, 21447363);

 		$bio = $generic->getTwitterBio($user);

 		$result = $score->getFakerScore($bio, $user);

 		$this->assertInstanceOf('Fakers\Score\Object\Score', $result);

 		$this->assertEquals(1000, $result->checks);
	}
}

class TestUser
{
	public $userid;

	public $twitterid;

	public function __construct($userid, $twitterid)
	{
		$this->userid = $userid;

		$this->twitterid = $twitterid;
	}
}