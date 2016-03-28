<?php namespace Helpers;

use Helpers\Session;

trait Csrf
{
	use Session;

	public function generateCsrfKey()
	{
		if (!isset($this->getSession()->csrf_key)) {
			return md5(
				base_convert(rand(9, 999999), 10, 36) .
	 			base_convert(time(), 10, 36)
			);
		}

		return $this->getSession()->csrf_key;
	}

	public function csrfKey()
	{
		$key = $this->generateCsrfKey();

		$this->setSession('csrf_key', $key);

		return $key;
	}

	public function csrfCheck($key)
	{
		$sessionKey = $this->getSession()->csrf_key;

		$this->unsetSession('csrf_key');

		return $key == $sessionKey;
	}
}