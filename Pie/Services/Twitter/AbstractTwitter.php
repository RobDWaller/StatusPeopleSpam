<?php namespace Services\Twitter;

use Services\Twitter\OAuth\TwitterOauth;
use Services\Config\Loader as Config;
use Services\Twitter\Object\User;
use Services\Twitter\Object\Users;
use Services\Twitter\Object\Ids;
use Services\Twitter\Object\Id;
use Exception\TwitterException;

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

	protected function checkResult()
	{
		if (!is_array($this->result) && property_exists($this->result, 'error'))  {
			$this->fail('Something went wrong with the Twitter Request [' . $this->result->error . ']');
		}

		if ($this->result == null) {
			$this->fail('Something went wrong with the Twitter Request [' . $this->result->error . ']');
		}

		if (!is_array($this->result) && property_exists($this->result, 'errors')) {
			$this->fail('Something went wrong with the Twitter Request [' . $this->result->errors[0]->message . ']');
		}
	}

	protected function fail($message)
	{
		throw new TwitterException($message);
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
			$result->timezone,
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

	protected function users($result)
	{
		foreach ($result as $r) {
			$users[] = $this->user($r);
		}

		return new Users($users);
	}

	protected function ids($result)
	{
		if ($result->ids != null) {
			foreach ($result->ids as $id) {
				$ids[] = new Id($id);
			}

			if ($ids != null) {
				return new Ids($ids, $result->next_cursor_str, $result->previous_cursor_str);
			}
		}
	}
}