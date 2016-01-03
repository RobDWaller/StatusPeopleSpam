<?php namespace Helpers;

trait Session
{
	public function startSession()
	{
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
	}

	public function setSession($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	public function getSession()
	{
		return (object) $_SESSION;
	}

	public function unsetSession($key)
	{
		unset($_SESSION[$key]);
	}

	public function destroySession()
	{
		session_unset();

		session_destroy();
	}
}