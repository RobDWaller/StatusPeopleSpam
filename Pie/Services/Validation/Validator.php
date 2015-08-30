<?php namespace Services\Validation;

use Respect\Validation\Validator as v;
use Services\Config\Loader;
use Services\Routes\Redirector;

class Validator
{
	protected $results;
	protected $valid;
	protected $errors;
	protected $config;
	protected $redirect;

	public function __construct()
	{
		$this->valid = true;
		$this->config = new Loader;
		$this->redirect = new Redirector;
	}

	public function isValid()
	{
		return $this->valid;
	}

	public function getErrors()
	{
		return $this->errors;
	}

	public function string($name, $string)
	{
		$this->results[$name] = v::string()->notEmpty()->validate($string);

		return $this;
	}

	public function check()
	{
		foreach ($this->results as $key => $value) {
			if (!$value)
			{
				$this->errors[] = $this->config->get('errors.' . $key);
				$this->valid = false;
			}
		}

		return $this;
	}

	public function didFail($validator,$location)
	{
		if (!$validator->isValid()) {
			$this->redirect->messages('errors',$validator->getErrors())->to($location);
		}
	}
}