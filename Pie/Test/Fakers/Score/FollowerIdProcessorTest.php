<?php namespace Test\Fakers\Score;

use Test\Build;
use Test\Faker\TwitterId;
use Fakers\Score\FollowerIdProcessor;
use Services\Twitter\Twitter;

class FollowerIdProcessorTest extends Build
{
	public function testGroupIdsByHundred()
	{
		$twitter = new Twitter;

		$scoreFollower = new FollowerIdProcessor($twitter);
		
		$id = new TwitterId;

		$ids = $id->getIds(200);

		$scoreFollower->groupIdsByHundred($ids);

		$this->assertEquals(2, $scoreFollower->getGroups());

		$ids = $id->getIds(200);

		$scoreFollower->groupIdsByHundred($ids);

		$this->assertEquals(4, $scoreFollower->getGroups());

		$scoreFollower->setGroups(0);
		$scoreFollower->setFollowerIdGroups(null);

		$ids = $id->getIds(5000);

		$scoreFollower->groupIdsByHundred($ids);

		$this->assertEquals(50, $scoreFollower->getGroups());
		$this->assertEquals(50, count($scoreFollower->getFollowerIdGroups()));
	}

	public function testBuildFollowerIdObjectSmall()
	{
		$twitter = new Twitter;

		$scoreFollower = new FollowerIdProcessor($twitter);
		
		$id = new TwitterId;

		$ids = $id->getIdGroups(1);

		$result = $scoreFollower->buildFollowerIdObject($ids, 5000);

		$this->assertInstanceOf('Fakers\Score\Object\FollowerId', $result);

		$this->assertEquals(5000, $result->followers);

		$this->assertEquals(50, $result->groupCount);

		$this->assertEquals(50, count($result->ids));
	}

	public function testBuildFollowerIdObjectMedium()
	{
		$twitter = new Twitter;

		$scoreFollower = new FollowerIdProcessor($twitter);
		
		$id = new TwitterId;

		$ids = $id->getIdGroups(5);

		$result = $scoreFollower->buildFollowerIdObject($ids, 25000);

		$this->assertInstanceOf('Fakers\Score\Object\FollowerId', $result);

		$this->assertEquals(25000, $result->followers);

		$this->assertEquals(250, $result->groupCount);

		$this->assertEquals(250, count($result->ids));
	}

	public function testBuildFollowerIdObjectLarge()
	{
		$twitter = new Twitter;

		$scoreFollower = new FollowerIdProcessor($twitter);
		
		$id = new TwitterId;

		$ids = $id->getIdGroups(10);

		$result = $scoreFollower->buildFollowerIdObject($ids, 200000);

		$this->assertInstanceOf('Fakers\Score\Object\FollowerId', $result);

		$this->assertEquals(200000, $result->followers);

		$this->assertEquals(500, $result->groupCount);

		$this->assertEquals(500, count($result->ids));
	}

}