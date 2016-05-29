<?php namespace Test\Fakers\Score;

use Test\Build;
use Fakers\Score\Stats;
use Test\Faker\TwitterUser;

class StatsTest extends Build
{
	public function testGetStats()
	{
		$user = new TwitterUser();

		$users = $user->getUserGroup(10);

		$stats = new Stats();

		$result = $stats->getStats($users);

		$this->assertInstanceOf('Fakers\Score\Object\Stats', $result);

		$this->assertEquals(1000, $result->count);

		$this->assertEquals(1000, ($result->follows250 + $result->follows500 + $result->follows1000));

		$this->assertEquals(1000, ($result->followers250 + $result->followers500 + $result->followers1000));

		$this->assertEquals(1000, ($result->lastTweetOne + $result->lastTweetThirty + $result->lastTweetHundred));
	}
}