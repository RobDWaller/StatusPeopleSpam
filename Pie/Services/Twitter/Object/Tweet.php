<?php namespace Services\Twitter\Object;

use Services\Object\AbstractObject;

class Tweet extends AbstractObject
{
	protected $id;

	protected $tweet;

	protected $createdDate;

	protected $createdTimestamp;

	protected $retweets;

	protected $favourites;

	protected $isRetweet;

	protected $isFavourite;

	protected $language;

	public function __construct($id, $tweet, $createdDate, $retweets, $favourites, $isRetweet, $isFavourite, $language)
	{
		$this->id = $id;

		$this->tweet = $tweet;

		$this->createdDate = $createdDate;

		$this->createdTimestamp = strtotime($createdDate);

		$this->createdDays = round(((time() - $this->createdTimestamp) / 3600) / 24);

		$this->retweets = $retweets;

		$this->favourites = $favourites;

		$this->isRetweet = $isRetweet;

		$this->isFavourite = $isFavourite;

		$this->language = $language;
	}
}