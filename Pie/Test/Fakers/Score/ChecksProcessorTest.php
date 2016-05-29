<?php namespace Test\Fakers\Score;

use Test\Build;
use Fakers\Score\ChecksProcessor;
use Services\Twitter\Twitter;
use Test\Faker\TwitterId;

class ChecksProcessorTest extends Build
{
	public function testCalculateChecksToMake()
	{
		$twitter = new Twitter;

		$checks = new ChecksProcessor($twitter);

		$result1 = $checks->calculateChecksToMake(1);

		$this->assertEquals(1, $result1);

		$result2 = $checks->calculateChecksToMake(2);

		$this->assertEquals(2, $result2);

		$result3 = $checks->calculateChecksToMake(5);

		$this->assertEquals(5, $result3);

		$result4 = $checks->calculateChecksToMake(99);

		$this->assertEquals(5, $result4);

		$result5 = $checks->calculateChecksToMake(100);

		$this->assertEquals(7, $result5);

		$result6 = $checks->calculateChecksToMake(299);

		$this->assertEquals(7, $result6);

		$result7 = $checks->calculateChecksToMake(300);

		$this->assertEquals(10, $result7);

		$result8 = $checks->calculateChecksToMake(500);

		$this->assertEquals(10, $result8);
	}

	/**
	 * @expectedException Exception\FakerScoreException
	 */

	public function testCalculateChecksToMakeFail()
	{
		$twitter = new Twitter;

		$checks = new ChecksProcessor($twitter);

		$checks->calculateChecksToMake(0);
	}

	public function testCalculateIncrement()
	{
		$twitter = new Twitter;

		$checks = new ChecksProcessor($twitter);

		$result1 = $checks->calculateIncrement(1, 5);

		$this->assertEquals(1, $result1);

		$result2 = $checks->calculateIncrement(5, 5);

		$this->assertEquals(1, $result2);

		$result3 = $checks->calculateIncrement(99, 5);

		$this->assertEquals(20, $result3);

		$result4 = $checks->calculateIncrement(100, 7);

		$this->assertEquals(14, $result4);

		$result5 = $checks->calculateIncrement(299, 7);

		$this->assertEquals(43, $result5);

		$result6 = $checks->calculateIncrement(300, 10);

		$this->assertEquals(30, $result6);

		$result7 = $checks->calculateIncrement(500, 10);

		$this->assertEquals(50, $result7);
	}

	public function testGetFollowerGroupsToCheckTiny()
	{
		$fake = new TwitterId;

		$ids = $fake->getHundredIdGroups(200);

		$twitter = new Twitter;

		$checks = new ChecksProcessor($twitter);

		$followerCheck = $checks->getFollowerGroupsToCheck(2, 1, 200, $ids);

		$this->assertInstanceOf('Fakers\Score\Object\FollowerCheck', $followerCheck);

		$this->assertEquals(2, $followerCheck->totalChecks);

		$this->assertEquals(2, count($followerCheck->checksArray));
	}

	public function testGetFollowerGroupsToCheckSmall()
	{
		$fake = new TwitterId;

		$ids = $fake->getHundredIdGroups(500);

		$twitter = new Twitter;

		$checks = new ChecksProcessor($twitter);

		$followerCheck = $checks->getFollowerGroupsToCheck(5, 1, 500, $ids);

		$this->assertEquals(5, $followerCheck->totalChecks);

		$this->assertEquals(5, count($followerCheck->checksArray));
	}

	public function testGetFollowerGroupsToCheckMedium()
	{
		$fake = new TwitterId;

		$ids = $fake->getHundredIdGroups(29900);

		$twitter = new Twitter;

		$checks = new ChecksProcessor($twitter);

		$followerCheck = $checks->getFollowerGroupsToCheck(7, 43, 29900, $ids);

		$this->assertEquals(7, $followerCheck->totalChecks);

		$this->assertEquals(7, count($followerCheck->checksArray));
	}

	public function testGetFollowerGroupsToCheckLarge()
	{
		$fake = new TwitterId;

		$ids = $fake->getHundredIdGroups(50000);

		$twitter = new Twitter;

		$checks = new ChecksProcessor($twitter);

		$followerCheck = $checks->getFollowerGroupsToCheck(10, 50, 50000, $ids);

		$this->assertEquals(10, $followerCheck->totalChecks);

		$this->assertEquals(10, count($followerCheck->checksArray));
	}
}