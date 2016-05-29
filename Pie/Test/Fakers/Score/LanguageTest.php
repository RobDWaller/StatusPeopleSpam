<?php namespace Test\Fakers\Score;

use Test\Build;
use Test\Faker\TwitterUser;
use Fakers\Score\Language;

class LanguageTest extends Build
{
	public function testGetLanguageStats()
	{
		$user = new TwitterUser();

		$users = $user->getUserGroup(5);

		$language = new Language();

		$result = $language->getLanguageStats($users);

		$this->assertInstanceOf('Fakers\Score\Object\Languages', $result);
	}
}