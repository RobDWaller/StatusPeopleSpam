<?php namespace Fakers\Score\Object;

use Services\Twitter\Object\Users;
use Services\Twitter\Object\User;
use Services\Object\AbstractObject;

class Score extends AbstractObject
{
	protected $twitterId;

	protected $screenName;

	protected $followers;

	protected $checks;

	protected $fakes;

	protected $inactives;

	/**
	 * A collection of fake followers found
	 */

	protected $fakeFollowers;

	/**
	 * The fake percentage
	 */

	protected $fake;

	protected $inactive;

	protected $good;

	protected $timestamp;

	protected $date;

	public function __construct(User $bio, $checks, $fakes, $inactives, Users $fakeFollowers = null)
	{
		$this->twitterId = $bio->twitterId;

		$this->screenName = $bio->screenName;

		$this->followers = $bio->followers;

		$this->checks = $checks;

		$this->fakes = $fakes;

		$this->inactives = $inactives;

		$this->fakeFollowers = $fakeFollowers;

		$this->fake = $this->calculate($this->checks, $this->fakes);

		$this->inactive = $this->calculate($this->checks, $this->inactives);

		$this->good = $this->setGood($this->fake, $this->inactive);

		$this->timestamp = time();

		$this->date = date('Y/m/d H:i:s', $this->timestamp);
	}

	public function calculate($checks, $value)
	{
		return round(($value / $checks) * 100);
	}

	public function setGood($fake, $inactive)
	{
		return 100 - ($fake + $inactive);
	}
}