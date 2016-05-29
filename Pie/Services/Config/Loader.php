<?php namespace Services\Config;

use Helpers\Server;

class Loader
{
	use Server;

	protected $configUrl;

	protected $value;

	public function __construct($url = null)
	{
		$this->configUrl = $url ? $url : $this->getServer()->DOCUMENT_ROOT . '/Pie/Config/';
	}

	protected function parts($fileString)
	{
		return !empty($fileString) ? explode('.', $fileString) : false;
	}

	protected function exists($file)
	{
		return file_exists($this->configUrl . $file . '.php');
	}

	protected function requireFile($file)
	{
		return $this->exists($file) ? require $this->configUrl . $file . '.php' : false;
	}

	protected function content($parts, $contents)
	{
		$this->value = $contents;

		foreach ($parts as $key => $value) {
			
			if ($key > 0) {
				
				if (!isset($this->value[$value])) {
					return false;
				} 

				$this->value = $this->value[$value];

			}
		}

		return $this->value;
	}

	public function get($fileString)
	{
		$parts = $this->parts($fileString);

		if (!$parts)
		{
			return false;
		}

		$contents = $this->requireFile($parts[0]);

		if (!$contents)
		{
			return false;
		}

		return $this->content($parts, $contents);
	}
}