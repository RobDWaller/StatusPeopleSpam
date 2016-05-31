<?php namespace Services\Twitter;

use Helpers\String;

class Twitter extends AbstractTwitter
{
	use String;

	public function getBio($token, $secret, $userId)
	{
		$this->client($token, $secret);

		$this->result = $this->twitter->get('users/show', ['user_id' => $userId]);

		$this->checkResult();

		return $this->user($this->result);
	}

	public function getFollowerIDsByName($token, $secret, $name, $cursor)
	{
		$this->client($token, $secret);

		$this->result = $this->twitter->get('followers/ids', ['screen_name' => $name, 'cursor' => $cursor]);

		$this->checkResult();
		
		return $this->ids($this->result);
	}

	public function getFollowerListByIdString($token, $secret, $idString, $count)
	{
		$this->client($token, $secret);

		$this->result = $this->twitter->post('users/lookup', ['user_id' => $idString, 'include_entities' => false]);
	
		$this->checkResult();

		return $this->users($this->result);
	}
}