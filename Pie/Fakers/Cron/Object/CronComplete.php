<?php namespace Fakers\Cron\Object;

use Fakers\Cron\Object\Results;
use Fakers\Cron\Object\Errors;
use Services\Object\AbstractObject;

class CronComplete extends AbstractObject
{
	protected $results;

	protected $errors;

	public function __construct(Results $results, Errors $errors)
	{
		$this->results = $results;

		$this->errors = $errors;
	}
}