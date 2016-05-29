<?php namespace Helpers;

use Services\Database\Collection;
use Exception\DatabaseException;
use Services\Database\Object\Parameter;
use Services\Database\Object\Parameters;
use PDO;

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

	public function getParameterType($parameter)
	{
		if ($parameter === true || $parameter === false) {
			return PDO::PARAM_BOOL;
		}

		if (is_int($parameter)) {
			return PDO::PARAM_INT;
		}	

		if (is_string($parameter)) {
			return PDO::PARAM_STR;
		}

		throw new DatabaseException('[' . $parameter . '] Not a valid PDO parameter type');
	}
}