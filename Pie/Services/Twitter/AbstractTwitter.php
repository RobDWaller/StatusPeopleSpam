<?php namespace Services\Twitter;

class AbstractTwitter
{
	protected $consumerKey = '';

	protected $consumerSecret = '';

	protected $twitter;

	protected $result;

	protected $code;

	public function client($token, $secret)
	{
		$this->twitter = TwitterOAuth($this->consumerKey, $this->consumerSecret, $token, $secret);
	}
}