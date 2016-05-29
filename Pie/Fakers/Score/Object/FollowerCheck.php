<?php namespace Fakers\Score\Object;

use Services\Object\AbstractObject;

class FollowerCheck extends AbstractObject
{
	protected $totalChecks;

	protected $checksArray;

	public function __construct($totalChecks, $checksArray)
	{
		$this->totalChecks = $totalChecks;

		$this->checksArray = $checksArray;
	}
}