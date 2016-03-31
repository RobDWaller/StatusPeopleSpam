<?php namespace Services\Twitter;

use Services\Twitter\OAuth\TwitterOauth;

class AbstractTwitter
{
	protected $consumerKey = '';

	protected $consumerSecret = '';

	protected $twitter;

	protected $result;

	protected $code;

	public function client($token, $secret)
	{
		$this->twitter = new TwitterOauth($this->consumerKey, $this->consumerSecret, $token, $secret);
	}
}