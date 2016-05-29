<?php namespace Fakers\Cron\Object;

use Services\Object\AbstractObject;

class Result extends AbstractObject
{
	protected $result;

	public function __construct($result)
	{
		$this->result = $result;
	}
} 