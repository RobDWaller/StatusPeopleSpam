<?php namespace Services\Messages;

use Services\Collection\AbstractCollection;
use Services\Messages\MessagesInterface;
use InvalidArgumentException;

class Collection extends AbstractCollection
{
	public function __construct(array $records)
	{
		$this->isValid($records);

		parent::__construct($records);
	}

	public function isValid(array $records)
	{
		foreach ($records as $value) {
			if (!$value instanceof MessagesInterface) {
				throw new InvalidArgumentException('Array is not instance of MessageInterface.');
			}
		}
	}
}