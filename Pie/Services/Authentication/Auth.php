<?php namespace Services\Authentication;

use Helpers\Redirector;
use Helpers\Session;
use Helpers\Cookie;
use Services\Config\Loader;

class Auth
{
	use Redirector;
	use Session;
	use Cookie;

	protected $config;

	public function __construct()
	{
		$this->config = new Loader;
	}

	public function id()
	{
		return !isset($this->user()->userid) ? false : $this->user()->userid;
	}

	public function user()
	{
		return $this->getSession();
	}

	public function isLoggedIn()
	{
		return $this->id() >= 1;
	}

	public function isAdminLoggedIn()
	{
		return isset($this->user()->admin_id) && $this->user()->admin_id >= 1;
	}

	public function isLogin()
	{
		if ($this->id() < 1) {
        	$this->logout();
		}
	}

	public function logout()
	{
		$this->destroy();

		$this->redirectTo('/Fakers/V/1');
	}

	public function destroy()
	{
		$this->destroySession();

		$this->destroyCookies();
	}

	public function login($userId, $primaryId, $type)
	{
		$this->setSession('userid', $userId);
		$this->setSession('primaryid', $primaryId);
		$this->setSession('type', $type);
	}

	public function set($key, $value)
	{
		$this->setSession($key, $value);
	}

	public function has($key)
	{
		return isset($this->user()->$key) && !empty($this->user()->$key);
	}

	public function getUserKey()
	{
		return $this->user()->user_key;
	}

	public function processType($data = null)
	{	
		return $data != null && $data->valid >= time() ? $data->type : 0;
	}
}