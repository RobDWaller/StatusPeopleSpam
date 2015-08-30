<?php

class Commands
{
	protected $class;
	protected $method;

	public function __construct(array $argv)
	{
		empty($argv[1]) || empty($argv[2]) ? $this->fail('No arguments set') : true;

		$this->class = $argv[1];
		$this->method = $argv[2];
	}

	public function make()
	{
		$class = new $this->class();
		
		$method = $this->method;		

		$class->$method();
	}

	protected function fail($message = 'Process Failed!!')
	{
		echo 'Error: '.$message;
		die();
	}
}
