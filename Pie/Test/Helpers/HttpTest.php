<?php namespace Test\Helpers;

use Test\Build;
use Helpers\Http;

class HttpTest extends Build 
{
	use Http;

	public function testGet()
	{
		$_GET['test_get'] = 'Hello';

		$this->assertEquals('Hello', $this->httpGet()->test_get);
	}

	public function testPost()
	{
		$_POST['test_post'] = 'World';

		$this->assertEquals('World', $this->httpPost()->test_post);
	}
}