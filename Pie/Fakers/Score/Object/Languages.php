<?php namespace Fakers\Score\Object;

use Services\Collection\AbstractCollection;

class Languages extends AbstractCollection
{
	public function __construct(array $languages)
	{
		foreach ($languages as $language) {
			if (!is_a($language, 'Fakers\Score\Object\Language')) {
				throw new FakerScoreException('Cannot build Language Collection');
			}
		}

		parent::__construct($languages);
	}
}