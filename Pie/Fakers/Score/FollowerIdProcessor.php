<?php namespace Fakers\Score;

use Services\Twitter\Twitter;
use Helpers\FakersScore;
use Services\Twitter\Object\Id;
use Services\Twitter\Object\Ids;
use Fakers\Score\Object\FollowerId;
use Exception\FakerScoreException;
use Services\Twitter\Object\Users;
use Fakers\Score\Object\FollowerIdGroup;

class FollowerIdProcessor
{
	use FakersScore;

	protected $twitter;

	protected $groups = 0;

	protected $followerIdGroups;

	public function __construct(Twitter $twitter)
	{
		$this->twitter = $twitter;
	}

	public function getGroups()
	{
		return $this->groups;
	}

	public function getFollowerIdGroups()
	{
		return $this->followerIdGroups;
	}

	public function setGroups($value)
	{
		$this->groups = $value;
	}

	public function setFollowerIdGroups($value)
	{
		$this->followerIdGroups = $value;
	}

	public function getFollowerIds($bio, $twitterDetails, $setRequests)
	{
		$this->groups = 0;

		$this->followerIdGroups = null;

		$requests = $this->calculateFollowerIdRequestsRequired($bio->followers, $setRequests);
		
		$followerIds = $this->generateAllFollowerIds($twitterDetails, $bio, $requests);

		return $this->buildFollowerIdObject($followerIds, $bio->followers);
	}

	public function buildFollowerIdObject(FollowerIdGroup $followerIds, $followers)
	{
		if ($followerIds->count() >= 1) {

			foreach ($followerIds as $ids) {
				$this->groupIdsByHundred($ids);
			}

			return new FollowerId(new FollowerIdGroup($this->followerIdGroups), $followers, $this->groups);
		}

		$this->fail('No Follower Id Groups');
	}

	public function groupIdsByHundred(Ids $ids)
	{
		$i = 0;

		if ($ids->count() >= 1) {
			
			foreach ($ids as $id) {

				$idsArray[] = $id; 
				
				if ($i == 99) {
					$this->followerIdGroups[$this->groups] = new Ids($idsArray);
					$this->groups++;
					$i = 0;
					$idsArray = null; 
				} else {
					$i++;
				}
			}	

			if (count($idsArray) >= 1) {
				$this->followerIdGroups[$this->groups] = new Ids($idsArray);
				$this->groups++;
			}

			return true;
		}

		$this->fail('No Ids Within Follower Group');
	}	

	protected function generateAllFollowerIds($twitterDetails, $bio, $requests)
	{
		$key = 0;
		$counter = 1;
		$cursor = '-1';
		
		while ($counter <= $requests) {
			$ids = $this->twitter->GetFollowerIDsByName(
				$twitterDetails->first()->token,
				$twitterDetails->first()->secret,
				$bio->screenName,
				$cursor
			);
			
			$followerIds[$key] = $ids;
			
			$cursor = $ids->nextCursor;
			
			$key++;
			$counter++;
		}

		return new FollowerIdGroup($followerIds);
	}
}