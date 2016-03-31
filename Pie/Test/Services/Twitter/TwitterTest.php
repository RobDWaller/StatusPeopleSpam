<?php namespace Test\Services\Twitter;

use Test\Build;
use Model\User;
use Services\Twitter\Twitter;

class TwitterTest extends Build
{
	protected $user;

	public function setUp()
	{
		parent::setUp();

		$user = new User;

		$this->user = $user->findUserDetailsByScreenName('StatusPeople');
	}

	public function testGetBio()
	{
		$twitter = new Twitter();

		$result = $twitter->getBio($this->user->first()->token, $this->user->first()->secret, $this->user->first()->twitterid);
	}
}