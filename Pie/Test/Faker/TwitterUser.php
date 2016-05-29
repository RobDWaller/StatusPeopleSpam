<?php namespace Test\Faker;

use Services\Twitter\Object\User;
use Services\Twitter\Object\Users;
use Fakers\Score\Object\UserGroup;

class TwitterUser extends AbstractFaker
{
	public function getUser()
	{
		$tweet = new TwitterTweet();

		return new User(
			$this->faker->numberBetween(1, 9999999),
			$this->faker->userName,
			$this->faker->country,
			$this->faker->timezone,
			$this->faker->sentence(6),
			$this->faker->url,
			$this->faker->boolean,
			$this->faker->numberBetween(1, 9999),
			$this->faker->numberBetween(1, 9999),
			$this->faker->numberBetween(1, 9999),
			$this->faker->date(),
			$this->faker->numberBetween(1, 9999),
			$this->faker->numberBetween(1, 9999),
			strtolower($this->faker->countryCode),
			$tweet->getRawTweet(),
			$this->faker->imageUrl(),
			$this->faker->numberBetween(1, 9999)
		);
	}

	public function getUsers($count = 1)
	{
		$c = 1;

		while ($c <= $count) {

			$userArray[] = $this->getUser();

			$c++;
		} 

		return new Users($userArray);
	}

	public function getUserGroup($count = 5)
	{
		$c = 1;

		while ($c <= $count) {

			$userArray[] = $this->getUsers(100);

			$c++;
		} 

		return new UserGroup($userArray);
	}
}