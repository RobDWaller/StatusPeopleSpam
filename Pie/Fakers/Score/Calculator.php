<?php namespace Fakers\Score;

use Services\Twitter\Object\Users;
use Services\Twitter\Object\User;
use Fakers\Score\Object\Score;
use Fakers\Score\Object\Status;
use Helpers\FakersScore;
use Fakers\Score\Object\UserGroup;

class Calculator
{
	use FakersScore;

	public function getScore(UserGroup $followerGroups, $bio)
	{
		$fakes = 0;
		$inactive = 0;
		$checks = 0;
		$result = [];

		foreach ($followerGroups as $followers)
		{
			foreach ($followers as $follower)
			{
				$faker = $this->getFakerStatus($follower);
	
				if ($faker->status == 1) {
					$fakes++;
					$fakeFollowers[] = $faker->follower;
				} elseif ($faker->status == 2) {
					$inactive++;
				}
				
				$checks++;
			}	
		}
		
		$fakerCollection = empty($fakeFollowers) ? null : new Users($fakeFollowers);

		return new Score($bio, $checks, $fakes, $inactive, $fakerCollection);
	}

	public function getFakerStatus(User $follower)
	{
			$followerFollowRatio = $this->getFollowerFollowRatio($follower);
			$ignoreFollower = $this->ignoreFollower($follower, $followerFollowRatio);

			if ($followerFollowRatio < 20 && $ignoreFollower === false)
			{
				if ($this->isFollowerFake($follower, $followerFollowRatio)) {
					return $this->fakerStatus(1, $follower, $followerFollowRatio);
				}				
			}
			
			if ($this->isFollowerInactive($follower)) {
				return $this->fakerStatus(2, $follower, $followerFollowRatio);
			}

			return $this->fakerStatus(3, $follower, $followerFollowRatio);		
	}
    
	protected function fakerStatus($status, $follower, $followerFollowRatio)
	{
		return new Status($status, $follower, $followerFollowRatio);
	}

	public function _CheckLanguageQuality($text)
	{
		//$query = '/\s{2,}||(.,)/';
		$result = false;
		
		$count = 0;
		
		$query = '/\.,|!,|,!|\.!|!-|\s_\w|\s\/\w|\*\.|\s,\s|\s;\s|\s:\s|\w,\w|\w\*\w|\w\|\w|\w!\w|\s{2,}|\.\||\.\?|\s\?\w|\w\?\w|\s@\s|\.@|\.~|\w#\w|\w~\w|,,|\.\*|\.-|\._|\s\'\w|\s!\w|\.;|;amp;|\sd\s|\se\s|\sf\s|\sg\s|\sh\s|\sj\sl\sm\s|\sn\s|\ss\s|\sv\s|\sw\s|\sz\s|\sporn\s|\sanal\s|\ssex\s|\sxxx\s/';
			
		if ($text['language']=='en')
		{
			if (!empty($text['description']))
			{
				preg_match_all($query,$text['description'],$matches1);
				
				$count += count($matches1[0]);
				
				if ($count<=2)
				{
					preg_match_all($query,$text['tweet'],$matches2);
					
					$count += count($matches2[0]);
					
					if ($count>=2&&$count<4)
					{
						if ($text['lasttweet']>=10)
						{
							$result = true;
						}
					}
					elseif ($count>=4)
					{
						$result = true;
					}
				}
				else
				{
					$result = true;
				}
			}
			else
			{
				preg_match_all($query,$text['tweet'],$matches3);
					
				$count += count($matches3[0]);
				
				if ($count>=2&&$count<4)
				{
					if ($text['lasttweet']>=10)
					{
						$result = true;
					}
				}
				elseif($count>=4)
				{
					$result = true;
				}
			}
		}
		
		//$this->errorschutney->PrintArray($count);
		
/* 		if($result)
		{
			$this->errorschutney->PrintArray($matches1);
			$this->errorschutney->PrintArray($matches2);
			$this->errorschutney->PrintArray($matches3);
			
			$this->errorschutney->PrintArray($text);
		} */
		
		return $result;
	}
}