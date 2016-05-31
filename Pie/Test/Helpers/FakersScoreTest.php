<?php namespace Test\Helpers;

use Test\Build;
use Helpers\FakersScore;

class FakersScoreTest extends Build
{
	use FakersScore;

	/**
	 * @expectedException Exception\FakerScoreException
	 */

	public function testCalculateFollowerIdRequestsRequiredWithZeroFollowers()
	{
		$requests1 = $this->calculateFollowerIdRequestsRequired(0, 10);
	}

	public function testCalculateFollowerIdRequestsRequired()
	{
		$requests1 = $this->calculateFollowerIdRequestsRequired(1, 10);

		$this->assertEquals(1, $requests1);

		$requests2 = $this->calculateFollowerIdRequestsRequired(150, 10);

		$this->assertEquals(1, $requests2);

		$requests3 = $this->calculateFollowerIdRequestsRequired(501, 10);

		$this->assertEquals(1, $requests3);

		$requests4 = $this->calculateFollowerIdRequestsRequired(1999, 10);

		$this->assertEquals(1, $requests4);

		$requests5 = $this->calculateFollowerIdRequestsRequired(5001, 10);

		$this->assertEquals(1, $requests5);

		$requests6 = $this->calculateFollowerIdRequestsRequired(5100, 10);

		$this->assertEquals(1, $requests6);

		$requests7 = $this->calculateFollowerIdRequestsRequired(9901, 10);

		$this->assertEquals(2, $requests7);

		$requests8 = $this->calculateFollowerIdRequestsRequired(25001, 10);

		$this->assertEquals(5, $requests8);

		$requests9 = $this->calculateFollowerIdRequestsRequired(48002, 10);

		$this->assertEquals(10, $requests9);

		$requests10 = $this->calculateFollowerIdRequestsRequired(253001, 10);

		$this->assertEquals(10, $requests10);
	}
}