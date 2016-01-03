<?php namespace Services\Messages;

use Services\Files\Facade as File;
use Services\Config\Facade as Config;
use Helpers\Session;
use Helpers\Serialize;
use Exception;

class Accessor
{
	use Session;
	use Serialize;

	public function has()
	{
		if ($this->messages()) {
			$file = $this->make();

			return $file->exists();
		}
		
		return false;
	}

	public function get()
	{
		if ($this->has()) {
			$file = $this->make();
			$this->unsetSession('messages');

			try {
				$result = $file->read(10000);

				$file->delete();
				
				return $this->unserialize($result);
			}
			catch(Exception $e) {
				echo $e->getMessage();
			}

			return $false;	
		}

		return false;
	}

	public function messagesDirectory()
	{
		return Config::make()->get('app.storage').'/Messages/';
	}

	protected function messages()
	{
		return $this->getSession()->messages;
	}

	protected function make()
	{
		return File::make($this->messagesDirectory(), $this->messages(), 'txt');
	}
}