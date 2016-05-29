<?php namespace Fakers\Score\Object;

use Services\Object\AbstractObject;

class FollowerId extends AbstractObject
{
	protected $ids;

	protected $followers;

	protected $groupCount;

	public function __construct(FollowerIdGroup $ids, $followers, $groupCount)
	{
		$this->ids = $ids;

		$this->followers = $followers;

		$this->groupCount = $groupCount;
	}
}