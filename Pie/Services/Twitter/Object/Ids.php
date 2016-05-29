<?php namespace Services\Twitter\Object;

use Services\Collection\AbstractCollection;
use Exception\TwitterException;

class Ids extends AbstractCollection
{
	public $nextCursor;

	public $previousCursor;

	public function __construct(array $ids, $nextCursor = null, $previousCursor = null)
	{
		foreach ($ids as $id) {
			if (!is_a($id, 'Services\Twitter\Object\Id')) {
				throw new TwitterException('Could not build Ids Collection');
			}
		}

		$this->nextCursor = $nextCursor;

		$this->previousCursor = $previousCursor;

		parent::__construct($ids);
	}
}