<?php namespace Services\Twitter\Object;

use Services\Collection\AbstractCollection;
use Exception\TwitterException;

class Users extends AbstractCollection
{
	public function __construct(array $users)
	{
		foreach ($users as $user) {
			if (!is_a($user, 'Services\Twitter\Object\User')) {
				throw new TwitterException('Could not build Users Collection');
			}
		}

		parent::__construct($users);
	}
}