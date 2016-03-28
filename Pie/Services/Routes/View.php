<?php namespace Services\Routes;

use Helpers\Dir;
use Helpers\Redirector;
use Helpers\Http;
use Services\Validation\Facade as Validator;
use Services\Messages\Html;

class View
{
	use Dir, Redirector, Http {
		Dir::getServer insteadOf Redirector;
	}

	protected $file;
	protected $data;
	
	public function setFile($file)
	{
		$this->file = $file;
	}

	public function addMessages()
	{
		try {
			$messages = Validator::make()->getMessages();

			if ($messages) {
				$html = new Html();

				$string = $html->buildMessages();

				$this->addData('Messages', $string);
			}
		}
		catch(Exception $e) {
			//Do Nothing
		}
		
	}

	public function addData($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function to($location)
	{
		$this->redirectTo($location);
	}

	public function filePath()
	{
		return $this->getRootDirectory() . '/Pie/' . $this->file;
	}

	public function load()
	{
		if (!file_exists($this->filePath())) {
			throw new \InvalidArgumentException('File ['.$this->filePath().'] cannot be found, 
				check it exists or is the right type');
		}

		if (is_array($this->getData())) {
			extract($this->getData());
		}
		
		require_once($this->filePath());

		die();
	}

	public function getData()
	{
		return $this->data;
	}

	public function post()
	{
		return $this->httpPost();
	}

	public function get()
	{
		return $this->httpGet();
	}
}