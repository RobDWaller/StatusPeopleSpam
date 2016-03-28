<?php namespace Services\Validation;

use Respect\Validation\Validator as v;
use Services\Config\Loader;
use Helpers\Redirector;
use Helpers\Random;
use Services\Messages\Facade as Message;
use Services\Messages\Collection;
use Services\Messages\Builder;
use Services\Messages\Accessor;
use Services\Authentication\Facade as Authentication;
use Helpers\Csrf;
use Exception\CsrfException;

class Validator
{
	use Redirector;

	use Random;

	use Csrf;

	protected $results;
	protected $valid;
	protected $errors;
	protected $config;

	public function __construct()
	{
		$this->valid = true;
		$this->config = new Loader;
	}

	public function isValid()
	{
		return $this->valid;
	}

	public function getErrors()
	{
		return empty($this->errors) ? false : new Collection($this->errors);
	}

	public function string($name, $string)
	{
		$this->results[$name] = v::string()->notEmpty()->validate($string);

		return $this;
	}

	public function email($name, $email)
	{
		$this->results[$name] = v::email()->notEmpty()->validate($email);

		return $this;
	}

	public function required($name, $value)
	{
		$this->results[$name] = v::notEmpty()->validate($value);

		return $this;
	}

	public function check()
	{
		foreach ($this->results as $key => $value) {
			if (!$value) {
				$this->setMessage($key);
				$this->valid = false;
			}
		}

		return $this;
	}

	public function setMessage($key)
	{
		$this->errors[] = $this->config->get('errors.' . $key) ? 
			Message::make('alert', [$this->config->get('errors.' . $key)], $key) : 
			Message::make('alert', ['Please enter a valid ' . $key], $key);
	}

	public function isFail($location)
	{
		if (!$this->isValid()) {
			$this->setMessages();
			$this->redirectTo($location);
		}
	}

	public function forceFail($location)
	{
		$this->setMessages();
		$this->redirectTo($location);
	}

	public function setMessages()
	{
		if ($this->getErrors()) {
			$builder = new Builder($this->getErrors());
			return $builder->set(
				Authentication::auth()->id() ? Authentication::auth()->id() : $this->rand() 
			);
		}

		return false;
	}

	public function getMessages()
	{
		$accessor = new Accessor();
		return $accessor->get();
	}

	public function csrf($key = null)
	{
		if (!$this->csrfCheck($key)) {
			throw new CsrfException('Invalid Form Submission');
		}	

		return $this;
	}
}