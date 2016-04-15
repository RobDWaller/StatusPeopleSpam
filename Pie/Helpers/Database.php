<?php namespace Helpers;

use Services\Database\Collection;
use Exception\DatabaseException;

trait Database
{
	public function inString($key, Collection $collection)
	{
		$inString = '';

		if ($collection->count() >= 1) {
			if (property_exists($collection->first(), $key)) {
				return $this->buildInString($key, $collection);
			}

			throw new DatabaseException('Collection does not have the key [' . $key . ']');
		}

		throw new DatabaseException('Collection is empty, cannot process');
	}

	protected function buildInString($key, $collection)
	{ 
		$inString = '';

		foreach ($collection as $c) {
			$inString .= $c->$key . ',';
		}

		return substr($inString, 0, -1);
	}
}