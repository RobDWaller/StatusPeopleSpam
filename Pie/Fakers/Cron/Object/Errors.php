<?php namespace Fakers\Cron\Object;

use Services\Collection\AbstractCollection;
use Exception\CronException;

class Errors extends AbstractCollection
{
	public function __construct(array $errors)
	{
		foreach ($errors as $error) {
			if (!is_a($error, 'Fakers\Cron\Object\Error')) {
				throw new CronException('Could not build cron error collection');
			}
		}

		parent::__construct($errors);
	}
}