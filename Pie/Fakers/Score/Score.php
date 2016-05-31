<?php namespace Fakers\Score;

use Fakers\Score\Calculator;
use Fakers\Score\ChecksProcessor;
use Fakers\Score\Follower;
use Fakers\Score\FollowerIdProcessor;
use Fakers\Score\Generic;
use Services\Twitter\Object\User;

class Score
{
	protected $calculator;

	protected $checksProcessor;

	protected $follower;

	protected $followerIdProcessor;

	protected $generic;

	protected $followers;

	public function __construct(Calculator $calculator, 
		ChecksProcessor 
		$checksProcessor, 
		Follower $follower, 
		FollowerIdProcessor $followerIdProcessor,
		Generic $generic)
	{
		$this->calculator = $calculator;

		$this->checksProcessor = $checksProcessor;

		$this->follower = $follower;

		$this->followerIdProcessor = $followerIdProcessor;

		$this->generic = $generic;
	}

	public function getFakerScore(User $bio, $user)
	{
		$followerIds = $this->followerIdProcessor->getFollowerIds(
			$bio, 
			$this->generic->getTwitterDetails($user->userid), 
			10
		);

		$checks = $this->checksProcessor->calculateFollowerChecks($followerIds);

		$this->followers = $this->follower->getFollowers($checks, $followerIds, $user);

		return $this->calculator->getScore($this->followers, $bio);
	}

	public function getFollowers()
	{
		return $this->followers;
	}
}