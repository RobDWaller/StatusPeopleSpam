<?php namespace Fakers\Score\Object;

use Services\Object\AbstractObject;

class Language extends AbstractObject
{
	protected $code;

	protected $language;

	protected $count;

	public function __construct($code, $language, $count)
	{
		$this->code = $code;

		$this->count = $count;

		$this->language = $language; 
	}
}