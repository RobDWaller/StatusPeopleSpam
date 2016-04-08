<?php namespace Services\Twitter\Object;

use Services\Twitter\Object\Tweet;

class User extends AbstractObject 
{
	protected $twitterId;

	protected $screenName;

	protected $location;

	protected $description;

	protected $website;

	protected $privateAccount;

	protected $followers;

	protected $follows;

	protected $listedCount;

	protected $createdDate;

	protected $createdTimestamp;

	protected $favourites;

	protected $tweets;

	protected $language;

	protected $lastTweet;

	protected $avatar;

	protected $following;

	public function __construct($twitterId, $screenName, $location, $description, $website, $privateAccount, 
		$followers, $follows, $listedCount, $createdDate, $favourites, $tweets, $language, $lastTweet, $avatar, $following)
	{
		$this->twitterId = $twitterId;

		$this->screenName = $screenName;

		$this->location = $location;

		$this->description = $description;

		$this->website = $website;

		$this->privateAccount = $privateAccount;

		$this->followers = $followers;

		$this->follows = $follows;

		$this->listedCount = $listedCount;

		$this->createdDate = $createdDate;

		$this->createdTimestamp = strtotime($createdDate);

		$this->favourites = $favourites;

		$this->tweets = $tweets;

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