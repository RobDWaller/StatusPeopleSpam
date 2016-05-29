<?php namespace Fakers\Score\Object;

use Services\Collection\AbstractCollection;

class FollowerIdGroup extends AbstractCollection
{
	public function __construct(array $idGroups)
	{
		foreach ($idGroups as $group) {
			if (!is_a($group, 'Services\Twitter\Object\Ids')) {
				throw new FakerScoreException('Could not build Follower Id Groups Collection');
			}	
		}
	
		parent::__construct($idGroups);
	}
}