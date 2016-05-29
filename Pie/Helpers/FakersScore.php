<?php namespace Helpers;

use Services\Twitter\Object\User;
use Services\Twitter\Object\Users;
use Services\Twitter\Object\Ids;
use Helpers\Regex;
use Exception\FakerScoreException;

trait FakersScore
{
	use Regex;

	public function calculateFollowerIdRequestsRequired($followers, $setRequests)
	{
		if ($followers == 0) {
			throw new FakerScoreException('No Followers cannot calculate requests.');
		}

		$requests = round($followers / 5000);
		
		if ($requests > $setRequests) {
			return $setRequests;
		}
		
		if ($requests < 1) {
			return 1;
		}

		return $requests;
	}

	public function idCollectionToDelimitedString(Ids $ids, $delimiter = ',')
	{
		foreach ($ids as $id) {
			$idString .= $id->id . ',';
		}

		return substr($idString, 0, -1);
	}

	public function getFollowerFollowRatio(User $follower)
	{
		if ($follower->followers >= 1 &&  $follower->follows >= 1) {
			return round(($follower->followers / $follower->follows) * 100); 
		}
		
		return 0;
	}

	public function ignoreFollower(User $follower, $followerFollowRatio)
	{
		if ($follower->follows == 0 && $follower->followers > 50)
		{
			return true;
		}
	
		if ($follower->createdDays <= 90 && $followerFollowRatio >= 10)
		{
			return true;
		}
		
		if ($follower->createdDays <= 45)
		{
			return true;
		}
	
		if ($follower->follows < 50)
		{
			return true;
		}

		return false;
	}

	public function isFollowerFake(User $follower, $followerFollowRatio)
	{
		if ($follower->tweets == 0 || $follower->followers == 0) {
			return true;
		}

		if ($followerFollowRatio <= 2) {
			return true;
		}
		
		if ($follower->follows >= 1000 && $follower->followers <= 100) {
			return true;
		}
		
		if ($followerFollowRatio < 10 && empty($follower->website) && $follower->favourites == 0) {
			return true;
		}
		
		if ($follower->tweetsPerDay <= 0.5 && !$this->stringContainsLink($follower->lastTweet->tweet)) {	
			return true;
		}
		
		return false;
	}

	public function isFollowerInactive(User $follower)
	{
		if ($follower->followers < 20 && $follower->follows < 20 && $follower->tweets < 20) {
			return true;
		}

		if ($follower->tweetsPerDay <= 0.1 || $follower->lastTweet->createdDays >= 90) {
			return true;
		}

		return false;
	}

	public function convertLanguageDataOldFormat($languageData)
	{
		if ($languageData->count() >= 1) {

			foreach ($languageData as $language) {
				$languageArray[]['name'] = $language->language;
				$languageArray[]['count'] = $language->count; 
			}

			return json_encode($languageArray);
		}

		return null;
	}

	public function convertStatsDataOldFormat($statsData)
	{
		$statsArray['count'] = $statsData->count;
		$statsArray['tweets_pd'] = $statsData->tweetsPerDay;
		$statsArray['followers'] = $statsData->followers;
		$statsArray['friends'] = $statsData->follows;
		$statsArray['one'] = $statsData->lastTweetOne;
		$statsArray['thirty'] = $statsData->lastTweetThirty;
		$statsArray['hundred'] = $statsData->lastTweetHundred;
		$statsArray['fo250'] = $statsData->followers250;
		$statsArray['fo500'] = $statsData->followers500;
		$statsArray['fo1000'] = $statsData->followers1000;
		$statsArray['fr250'] = $statsData->follows250;
		$statsArray['fr500'] = $statsData->follows500;
		$statsArray['fr1000'] = $statsData->follows1000;

		return json_encode($statsArray);
	}

	public function convertSpamDataOldFormat(Users $spamData = null)
	{
		if ($spamData !== null && $spamData->count() >= 1) {
			
			foreach ($spamData as $spam) {
				$spamArray[]['id'] = $spam->twitterId;
				$spamArray[]['screen_name'] = $spam->screenName;
				$spamArray[]['location'] = $spam->location;
				$spamArray[]['timezone'] = $spam->timeZone;
				$spamArray[]['language'] = $spam->language;
				$spamArray[]['friends'] = $spam->follows;
				$spamArray[]['followers'] = $spam->followers;
				$spamArray[]['tweets'] = $spam->tweets;
				$spamArray[]['tweetsperday'] = $spam->tweetsPerDay;
				$spamArray[]['description'] = $spam->description;
				$spamArray[]['website'] = $spam->website;
				$spamArray[]['image'] = $spam->avatar;
				$spamArray[]['following'] = $spam->following;
				$spamArray[]['favourites'] = $spam->favourites;
				$spamArray[]['listed'] = $spam->listedCount;
				$spamArray[]['created'] = $spam->createdDays;
				$spamArray[]['lasttweet'] = $spam->lastTweet->createdDays;
				$spamArray[]['tweet'] = $spam->lastTweet->tweet;
				$spamArray[]['tweet_retweet'] = $spam->lastTweet->isRetweet;
				$spamArray[]['tweet_favourite'] = $spam->lastTweet->isFavourite;
			}

			return json_encode($spamArray);
		}

		return null;
	}

	public function buildSpamInsertString($userId, $spamData)
	{
		$s = 0;
	
		$insertstring = '';

		foreach ($spamData as $spam)	{

			if ($s < 1000) {

				$insertstring .= '(' . $userID . ',' . $spam->twitterId . ',"' 
					. $spam->screenName . '","' . $spam->avatar . '",' . time() . '),';
			}

			$s++;
		}

		return substr($insertstring,0,-1);
	}

}