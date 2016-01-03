<?php namespace Helpers;

trait Cookie
{
	public function destroyCookies()
	{
		foreach ($_COOKIE as $key => $obj)
		{
			setcookie($key, '', time() - 10, '/');
		}
	}
}