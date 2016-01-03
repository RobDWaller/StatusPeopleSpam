<?php namespace Services\Database;

use Services\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	public function __construct(array $records) {

		$data = [];

		foreach ($records as $r) {
			$data[] = (object) ($r);
		}

		parent::__construct($data);
	}
}