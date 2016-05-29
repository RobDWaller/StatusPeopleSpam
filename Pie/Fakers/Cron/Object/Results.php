<?php namespace Fakers\Cron\Object;

use Services\Collection\AbstractCollection;
use Exception\CronException;

class Results extends AbstractCollection
{
	public function __construct(array $results)
	{
		foreach ($results as $result) {
			if (!is_a($result, 'Fakers\Cron\Object\Result')) {
				throw new CronException('Cron exception could not ');
			}
		}

		parent::__construct($results);
	}
}