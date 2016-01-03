<?php namespace Helpers;

use Helpers\Random;
use Services\Authentication\Auth;

trait Key
{
	use Random;

	public function generateKey(Auth $auth, $salt)
	{
		if (!$auth->has($salt . '_key')) {
			$auth->set($salt . '_key', $this->numKey($salt));

			return $key;
		}

		return null;
	}
}