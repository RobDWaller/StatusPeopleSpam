<?php namespace Services\Twitter\Object;

use Services\Twitter\Object\Tweet;
use Services\Object\AbstractObject;

class User extends AbstractObject 
{
	protected $twitterId;

	protected $screenName;

	protected $location;

	protected $timeZone;

	protected $description;

	protected $website;

	protected $privateAccount;

	protected $followers;

	protected $follows;

	protected $listedCount;

	protected $createdDate;

	protected $createdTimestamp;

	protected $createdDays;

	protected $favourites;

	protected $tweets;

	protected $tweetsPerDay;

	protected $language;

	protected $lastTweet;

	protected $avatar;

	protected $following;

	public function __construct($twitterId, $screenName, $location, $timeZone, $description, 
		$website, $privateAccount, $followers, $follows, $listedCount, $createdDate, 
		$favourites, $tweets, $language, $lastTweet, $avatar, $following)
	{
		$this->twitterId = $twitterId;

		$this->screenName = $screenName;

		$this->location = $location;

		$this->timeZone = $timeZone;

		$this->description = $description;

		$this->website = $website;

		$this->privateAccount = $privateAccount;

		$this->followers = $followers;

		$this->follows = $follows;

		$this->listedCount = $listedCount;

		$this->createdDate = $createdDate;

		$this->createdTimestamp = strtotime($createdDate);

		$this->createdDays = round(((time() - $this->createdTimestamp) / 3600) / 24);

		$this->favourites = $favourites;

		$this->tweets = $tweets;

		$this->tweetsPerDay = $this->tweets != 0 && $this->createdDays != 0 ? round($this->tweets / $this->createdDays) : 0;

		$this->language = $language;

		$this->lastTweet = new Tweet(
			$lastTweet->id,
			$lastTweet->text,
			$lastTweet->created_at,
			$lastTweet->retweet_count,
			$lastTweet->favorite_count,
			$lastTweet->retweeted,
			$lastTweet->favorited,
			$lastTweet->lang
		);

		$this->avatar = $avatar;

		$this->following = !$following || $following == null ? false : true; 
	}
}