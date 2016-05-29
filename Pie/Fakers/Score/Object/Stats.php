<?php namespace Fakers\Score\Object;

use Services\Object\AbstractObject;

class Stats extends AbstractObject
{
	protected $count;

	protected $tweetsPerDay;

	protected $followers;

	protected $follows;

	protected $lastTweetOne;

	protected $lastTweetThirty;

	protected $lastTweetHundred;

	protected $follows250;

	protected $follows500;

	protected $follows1000;

	protected $followers250;

	protected $followers500;

	protected $followers1000;

	public function __construct($count, $tweetsPerDay, $followers, $follows, $lastTweetOne,
		$lastTweetThirty, $lastTweetHundred, $followers250, $followers500, $followers1000,
		$follows250, $follows500, $follows1000)
	{
		$this->count = $count;

		$this->tweetsPerDay = $tweetsPerDay;
			
		$this->followers = $followers;

		$this->follows = $follows;

		$this->lastTweetOne = $lastTweetOne;

		$this->lastTweetThirty = $lastTweetThirty;
		
		$this->lastTweetHundred = $lastTweetHundred;
		
		$this->followers250 = $followers250;
		
		$this->followers500 = $followers500;
		
		$this->followers1000 = $followers1000;
		
		$this->follows250 = $follows250;
		
		$this->follows500 = $follows500;
		
		$this->follows1000 = $follows1000;
	}
}