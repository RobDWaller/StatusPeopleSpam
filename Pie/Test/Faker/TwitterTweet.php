<?php namespace Test\Faker;

use Services\Twitter\Object\Tweet;

class TwitterTweet extends AbstractFaker
{
	public function getRawTweet()
	{
		return new rawTweet(
			$this->faker->numberBetween(1, 9999999),
			$this->faker->sentence(6),
			$this->faker->date('Y-m-d', 'now'),
			$this->faker->numberBetween(0, 9),
			$this->faker->numberBetween(0, 9),
			$this->faker->boolean,
			$this->faker->boolean,
			$this->faker->countryCode
		);
	}
}

class rawTweet
{
	public $id;

	public $text;

	public $created_at;

	public $retweet_count;

	public $favorite_count;

	public $retweeted;

	public $favorited;

	public $lang;

	public function __construct($id, $text, $created_at, $retweet_count, $favourite_count, 
		$retweeeted, $favorited, $lang)
	{
		$this->id = $id;

		$this->text = $text;

		$this->created_at = $created_at;

		$this->retweet_count = $retweet_count;

		$this->favourite_count = $favourite_count;

		$this->retweeted = $retweeted;

		$this->favorited = $favorited;

		$this->lang = $lang;
	}
}