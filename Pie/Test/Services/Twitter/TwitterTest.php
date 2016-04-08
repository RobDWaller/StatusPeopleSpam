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
	
		$this->assertInstanceOf('Services\Twitter\Object\User', $result);

		$this->assertObjectHasAttribute('twitterId', $result);

		$this->assertFalse($result->twitterId === null);

		$this->assertObjectHasAttribute('screenName', $result);

		$this->assertFalse($result->screenName === null);

		$this->assertObjectHasAttribute('location', $result);

		$this->assertFalse($result->location === null);

		$this->assertObjectHasAttribute('description', $result);

		$this->assertFalse($result->description === null);

		$this->assertObjectHasAttribute('website', $result);

		$this->assertFalse($result->website === null);

		$this->assertObjectHasAttribute('privateAccount', $result);

		$this->assertFalse($result->privateAccount === null);

		$this->assertGreaterThan(0, $result->followers);

		$this->assertGreaterThan(0, $result->follows);

		$this->assertGreaterThan(0, $result->listedCount);

		$this->assertObjectHasAttribute('createdDate', $result);

		$this->assertFalse($result->createdDate === null);

		$this->assertObjectHasAttribute('createdTimestamp', $result);

		$this->assertFalse($result->createdTimestamp === null);

		$this->assertGreaterThan(0, $result->favourites);

		$this->assertGreaterThan(0, $result->tweets);

		$this->assertObjectHasAttribute('language', $result);

		$this->assertFalse($result->language === null);

		$this->assertInstanceOf('Services\Twitter\Object\Tweet', $result->lastTweet);	

		$this->assertObjectHasAttribute('avatar', $result);

		$this->assertFalse($result->avatar === null);

		$this->assertObjectHasAttribute('following', $result);

		$this->assertFalse($result->following === null);	
	}

	/**
	 * @expectedException Exception\TwitterException
	 */

	public function testGetBioFail()
	{
		$twitter = new Twitter();

		$result = $twitter->getBio($this->user->first()->token, $this->user->first()->secret, $this->user->first()->twitterid);

		$result->helloWorld;
	}
}