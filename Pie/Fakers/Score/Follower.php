<?php namespace Fakers\Score;

use Services\Twitter\Twitter;
use Fakers\Score\Object\FollowerCheck;
use Fakers\Score\Object\FollowerId;
use Services\Twitter\Object\Users;
use Helpers\FakersScore;
use Fakers\Score\Object\UserGroup;

class Follower
{
	use FakersScore;

	public function __construct(Twitter $twitter)
	{
		$this->twitter = $twitter;
	}

	public function getFollowers(FollowerCheck $followerChecks, FollowerId $followerIds, $user)
	{
 		$ids = $followerIds->ids->toArray();	

		foreach ($followerChecks->checksArray as $check) {
			
			if (isset($ids[$check])) {
				
				$foundFollowers = false;
				$requestCount = 0;

				while(!$foundFollowers) {

					if ($requestCount < 3) {

						$idString = $this->idCollectionToDelimitedString($ids[$check]);
						$followers = $this->twitter->getFollowerListByIdString($user->token, $user->secret, $idString, 100);

						if ($followers->count() >= 1) {
							$foundFollowers = true;
						}

						$requestCount++;

					} else {
						$foundFollowers = true;
					}

				}

				$followersGroupsArray[] = $followers;

			}
		}
	
		return new UserGroup($followersGroupsArray);

	}
}