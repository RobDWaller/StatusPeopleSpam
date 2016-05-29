<?php namespace Fakers\Score\Object;

use Services\Object\AbstractObject;

class Status extends AbstractObject
{
	protected $status;

	protected $statusString;

	protected $follower;

	protected $followerFollowRatio;

	public function __construct($status, $follower, $followerFollowRatio)
	{
		$this->status = $status;

		$this->statusString = $this->setStatusString($status);

		$this->follower = $follower;

		$this->followerFollowRatio = $followerFollowRatio;
	}

	public function setStatusString($status)
	{
		if ($status == 1) {
			return 'Fake';
		}

		if ($status == 2) {
			return 'Inactive';
		}

		return 'Good';
	}
}