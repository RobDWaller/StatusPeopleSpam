<?php namespace Services\Twitter;

use Services\Twitter\OAuth\TwitterOauth;
use Services\Config\Loader as Config;

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
}