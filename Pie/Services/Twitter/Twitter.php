<?php namespace Services\Twitter;

class Twitter extends AbstractTwitter
{
	public function getBio($token, $secret)
	{
		$this->client($token, $secret);

		$this->result = $this->twitter->get('users/show', ['user_id' => $userid]);

		return $this->user();
	}
}