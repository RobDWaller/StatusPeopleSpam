<?php namespace Fakers\Cron\Object;

use Services\Object\AbstractObject;

class Error extends AbstractObject
{
	protected $message;

	protected $line;

	protected $file;

	protected $timestamp; 

	protected $date;

	public function __construct($message, $line, $file)
	{
		$this->message = $message;

		$this->line = $line;

		$this->file = $file;

		$this->timestamp = time();

		$this->date = date('Y/m/d H:i:s', $this->timestamp);
	}
}