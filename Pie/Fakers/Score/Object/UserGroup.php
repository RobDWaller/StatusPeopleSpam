<?php namespace Fakers\Score\Object;

use Services\Collection\AbstractCollection;
use Exception\FakerScoreException;

class UserGroup extends AbstractCollection
{
	public function __construct(array $users)
	{
		foreach ($users as $user) {
			
			if (!is_a($user, 'Services\Twitter\Object\Users')) {
				throw new FakerScoreException('Could not build User Group collection');
			}	
		}

		parent::__construct($users);
	}
}