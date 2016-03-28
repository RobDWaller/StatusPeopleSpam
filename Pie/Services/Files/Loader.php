<?php namespace Services\Files;

use UnexpectedValueException;

class Loader
{
	protected $directory;

	protected $file;

	protected $type;

	protected $fileString;

	protected $handle;

	protected $contents;

	public function __construct($directory, $file = null, $type = null)
	{
		$this->directory = $directory;

		$this->file = $file;

		$this->type = $type;

		$this->fileString = $directory.$file.'.'.$type;
	}

	public function directoryExists()
	{
		return is_dir($this->directory);
	}

	public function exists()
	{
		return file_exists($this->fileString);
	}

	protected function open($mode)
	{
		$this->handle = fopen($this->fileString, $mode);

		if (!$this->handle) {
			$this->fail('Failed to open file in mode [' . $mode . ']');
		}
	}

	protected function close()
	{
		fclose($this->handle);
	}

	public function read($length = 0)
	{
		if (!$this->exists()) {
			$this->fail('File does not exist and cannot be read.');
		}

		$this->open('r');

		return $this->getContents($length);
	}

	protected function getContents($length = 0)
	{
		return fread($this->handle, $length);
	}

	public function write($content)
	{
		if (!$this->exists()) {
			$this->fail('File does not exists and cannot be written');
		}

		$this->open('w');

		return fwrite($this->handle, $content);
	}

	public function create()
	{
		if ($this->exists()) {
			$this->fail('File already exists');
		}

		$this->makeDirectory();

		$this->open('x+');

		return $this;
	}

	public function makeDirectory()
	{
		if(!$this->directoryExists()) {
			return mkdir($this->directory) ? true :
				$this->fail('Failed to create directory [' . $this->directory . ']');
		}

		return false;
	}

	public function delete()
	{
		if (!$this->exists()) {
			$this->fail('File does not exist');
		}

		return unlink($this->fileString);
	}

	public function deleteDirectory()
	{
		return $this->directoryExists() ? rmdir($this->directory) : true;
	}

	private function fail($message)
	{
		throw new UnexpectedValueException($message.' ['.$this->fileString.']');
	}

}