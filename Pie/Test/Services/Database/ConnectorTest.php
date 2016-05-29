<?php namespace Test\Services\Database;

use Test\Build;
use Services\Database\Connector;

class ConnectorTest extends Build
{
	public function testDefineParameters()
	{
		$connector = new Connector();

		$parameters = [
			'userid' => 1,
			'live' => true,
			'name' => 'James'
		];

		$parameterCollection = $connector->defineParameters($parameters);

		$this->assertInstanceOf('Services\Database\Object\Parameters', $parameterCollection);

		foreach ($parameterCollection as $parameter) {
			$this->assertInstanceOf('Services\Database\Object\Parameter', $parameter);
		}

		$parameterCollection->rewind();

		$parameter1 = $parameterCollection->first();

		$this->assertEquals('userid', $parameter1->name);
		$this->assertEquals(1, $parameter1->value);
		$this->assertEquals(1, $parameter1->type);
		$this->assertEquals(1, $parameter1->length);

		$parameter2 = $parameterCollection->next();

		$this->assertEquals('live', $parameter2->name);
		$this->assertEquals(true, $parameter2->value);
		$this->assertEquals(5, $parameter2->type);
		$this->assertEquals(1, $parameter2->length);

		$parameter3 = $parameterCollection->next(); 

		$this->assertEquals('name', $parameter3->name);
		$this->assertEquals('James', $parameter3->value);
		$this->assertEquals(2, $parameter3->type);
		$this->assertEquals(5, $parameter3->length);
	}

	/**
	 * @expectedException Exception\DatabaseException
	 */

	public function testDefineParametersFail()
	{
		$connector = new Connector();

		$parameters = [
			'userid' => null
		];

		$connector->defineParameters($parameters);
	}

	/**
	 * @expectedException Exception\DatabaseException
	 */

	public function testSelectRecordsFail()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$connector->selectRecords('SELECT PIES FROM CHEESE');
	}

	public function testSelectRecords()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$result = $connector->selectRecords('SELECT * FROM spsp_users LIMIT 0, 5');

		$this->assertTrue(is_array($result));
	}

	public function testSelectCount()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$result = $connector->selectCount('SELECT COUNT(*) FROM spsp_users');

		$this->assertTrue(is_int($result));
	}

	/**
	 * @expectedException Exception\DatabaseException
	 */

	public function testSelectCountFail()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$connector->selectCount('SELECT FROM spsp_users');
	}

	/**
	 * @expectedException Exception\DatabaseException
	 */

	public function testInsertRecordFail()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$connector->insertRecord("INSERT INTO spsp_users (twitterid, token, secret) VALUES ('James', 1, )");
	}

	public function testInsertRecord()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$result = $connector->insertRecord("INSERT INTO spsp_users (twitterid, token, secret) VALUES (123, 'abc', 'fgh')");
	
		$this->assertTrue(is_int($result));
	}

	public function testUpdateRecord()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$params = ['token' => 'ghi', 'twitterid' => 123];

		$result = $connector->updateRecord("UPDATE spsp_users SET token = :token WHERE twitterid = :twitterid", $params);

		$this->assertEquals(1, $result);
	}

	public function testUpdateRecordFailOne()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$params = ['token' => 'ghi', 'twitterid' => 'asdd98812iiadiasdw99190'];

		$result = $connector->updateRecord("UPDATE spsp_users SET token = :token WHERE twitterid = :twitterid", $params);

		$this->assertEquals(0, $result);
	}

	/**
	 * @expectedException Exception\DatabaseException
	 */

	public function testUpdateRecordFailTwo()
	{
		$connector = new Connector();

		$connector->setConnection('statuspeople_spam');

		$params = ['token' => 'ghi', 'twitterid' => 'asdd98812iiadiasdw99190'];

		$result = $connector->updateRecord("UPDATE spsp_users SET token = :token WHEE twitterid = :twitterid", $params);

		$this->assertEquals(0, $result);
	}
}