<?php namespace Services\Twitter;

use Services\Twitter\OAuth\TwitterOauth;
use Services\Config\Loader as Config;
use Services\Twitter\Object\User;

class AbstractTwitter
{
	protected $consumerKey;

	protected $consumerSecret;

	protected $twitter;

	protected $result;

	protected $code;

	public function client($token, $secret)
	{
		if (!$this->keysSet()) {
			$this->setKeys();
		}

		$this->twitter = new TwitterOauth($this->consumerKey, $this->consumerSecret, $token, $secret);
	}

	public function setConsumerKey($key)
	{
		$this->consumerKey = $key;
	}

	public function setConsumerSecret($secret)
	{
		$this->consumerSecret = $secret;
	}

	protected function keysSet()
	{
		return isset($this->consumerKey) && isset($this->consumerSecret);
	}

	protected function setKeys()
	{
		$config = new Config;

		$this->consumerKey = $config->get('twitter.key');

		$this->consumerSecret = $config->get('twitter.secret');
	} 

	protected function user($result)
	{
		return new User(
			$result->id,
			$result->screen_name,
			$result->location,
			$result->description,
			$result->url,
			$result->protected,
			$result->followers_count,
			$result->friends_count,
			$result->listed_count,
			$result->created_at,
			$result->favourites_count,
			$result->statuses_count,
			$result->lang,
			$result->status,
			$result->profile_image_url_https,
			$result->following
		);
	}
}