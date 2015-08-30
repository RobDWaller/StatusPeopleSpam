<?php namespace Services\Authentication;

class Session
{
	public function set($key,$value)
	{
		$_SESSION[$key] = $value;
	}

	public function get($key)
	{
		return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
	}

	public function getAll()
	{
		return $_SESSION;
	}

	public function destroy()
	{
		session_unset();

		session_destroy();
	}

	public function destroyCookies()
	{
		foreach ($_COOKIE as $key => $obj)
		{
			setcookie($key,'',time() - 10,'/');
		}
	}
}