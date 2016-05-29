<?php namespace Test\Helpers;

use Helpers\Database;
use Services\Database\Collection;
use Test\Build;
use Mockery as m;

class DatabaseTest extends Build
{
	use Database;

	public function testInString()
	{
		$results[] = ['userid' => 2, 'name' => 'jo'];
		$results[] = ['userid' => 6, 'name' => 'chris'];
		$results[] = ['userid' => 13, 'name' => 'dave'];

		$collection = new Collection($results);

		$string = $this->inString('userid', $collection);

		$this->assertEquals('2,6,13', $string);
	}

	/**
	 * @expectedException Exception\DatabaseException
	 */

	public function testInStringCollectionFail()
	{
		$mock = m::mock('Services\Database\Collection');

		$mock->shouldReceive('count')
			->andReturn(0);

		$this->inString('userid', $mock);
	}

	/**
	 * @expectedException Exception\DatabaseException
	 */

	public function testInStringKeyFail()
	{
		$results[] = ['id' => 2, 'name' => 'jo'];
		$results[] = ['id' => 6, 'name' => 'chris'];
		$results[] = ['id' => 13, 'name' => 'dave'];

		$collection = new Collection($results);

		$string = $this->inString('userid', $collection);

		$this->assertEquals('2,6,13', $string);
	}

	public function testGetParameterType()
	{
		$result = $this->getParameterType(3);

		$this->assertEquals(1, $result);

		$result = $this->getParameterType('a string');

		$this->assertEquals(2, $result);

		$result = $this->getParameterType(false);

		$this->assertEquals(5, $result);

		$result = $this->getParameterType(9.99);

		$this->assertEquals(1, $result);
	}
}