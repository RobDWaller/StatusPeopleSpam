<?php namespace Test\Model;

use Model\Check;
use Services\Database\Collection;
use Test\Build;

class CheckTest extends Build
{
	public function testGetUsersToCheck()
	{
		$check = new Check();

		$collection = new Collection(
			[
				['userid' => 198192466],
				['userid' => 31386162 ]
			]
		);

		$result = $check->getUsersToCheck($collection, time());

		$this->assertInstanceOf('Services\Database\Collection', $result);

		$this->assertEquals(1, $result->count());

		$this->assertInstanceOf('stdClass', $result->first());
	}
}