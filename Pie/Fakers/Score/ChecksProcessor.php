<?php namespace Fakers\Score;

use Services\Twitter\Twitter;
use Helpers\FakersScore;
use Services\Twitter\Object\Id;
use Services\Twitter\Object\Ids;
use Fakers\Score\Object\FollowerId;
use Exception\FakerScoreException;
use Fakers\Score\Object\FollowerCheck;
use Services\Twitter\Object\Users;
use Fakers\Score\Object\FollowerIdGroup;

class ChecksProcessor
{
	use FakersScore;

	public function __construct(Twitter $twitter)
	{
		$this->twitter = $twitter;
	}

	public function calculateFollowerChecks(FollowerId $followerId)
	{
		$checks = $this->calculateChecksToMake($followerId->groupCount);
		
		$increment = $this->calculateIncrement($followerId->groupCount, $checks);
		
		return $this->getFollowerGroupsToCheck($checks, $increment, $followerId->followers, $followerId->ids);
	}

	/**
	 * Calculate the number of checks to make based on the number of follower id groups there are.
	 * Follower Ids are grouped into arrays of 100.
	 */

	public function calculateChecksToMake($groups)
	{
		if ($groups == 0) {
			throw new FakerScoreException('No follower groups to check');
		}

		if ($groups < 5) {
			return $groups;
		}
		
		if ($groups >= 5 && $groups < 100) {
			return 5;
		}
		
		if ($groups >= 100 && $groups < 300) {
			return 7;
		}
		
		if ($groups >= 300) {
			return 10;
		}
	}

	/**
	 * Calculates how large the increment should be for counting through the groups of follower IDs.
	 * Example if you have 20 groups of ids you may wish to sample ever third group so your increment
	 * is 3. Follower Ids are grouped into arrays of 100.
	 */

	public function calculateIncrement($groups, $checks)
	{
		$increment = round($groups / $checks, 0, PHP_ROUND_HALF_DOWN);
		
		if ($increment < 2) {
			$increment = 1;
		}

		return $increment;
	}

	/**
	 * Returns an object declaring which groups of follower IDs should be sampled.
	 * Example: if you have 20 groups of ids you may wish to sample group 1, 5, 10 and 15
	 */

	public function getFollowerGroupsToCheck($checks, $increment, $followers, FollowerIdGroup $ids)
	{
		$idGroups = $ids->toArray();
		
		$y = 0;
		$z = 1;
		
		while ($z <= $checks) {
			
			if (isset($idGroups[round($y * $increment)]) && 
				$idGroups[round($y * $increment)]->count() < 100 && 
				$z == $checks && 
				$followers > 500) {

				$checksArray[$y] = round(($y * $increment) - 1);

			} else {

				$checksArray[$y] = round($y * $increment);  

			}  
			
			$y++;
			$z++;
		}
		
		return new FollowerCheck($checks, $checksArray);
	}
}