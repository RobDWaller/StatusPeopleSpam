<?php namespace Services\Twitter;

class Twitter extends AbstractTwitter
{
	public function getBio($token, $secret, $userId)
	{
		$this->client($token, $secret);

		$this->result = $this->twitter->get('users/show', ['user_id' => $userId]);

		var_dump($this->result);

		die();

		return $this->user();
	}
}