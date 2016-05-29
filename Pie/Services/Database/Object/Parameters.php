<?php namespace Services\Database\Object;

use Services\Collection\AbstractCollection;
use Exception\DatabaseException;

class Parameters extends AbstractCollection
{
	public function __construct(array $parameters)
	{
		foreach ($parameters as $parameter) {
			if (!is_a($parameter, 'Services\Database\Object\Parameter')) {
				throw new DatabaseException('Could not build Parameters Collection');
			}	
		}

		parent::__construct($parameters);
	}
}