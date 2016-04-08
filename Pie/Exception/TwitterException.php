<?php namespace Exception;

use Exception;

class TwitterException extends Exception
{
	public function __construct($message, $code = 0, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}