<?php namespace Services\Authentication;

use Services\Authentication\Auth;
use Services\Authentication\Password;

class Facade
{
	
	public static function auth()
	{
		return new Auth();
	}

	public static function password()
	{
		return new Password(PASSWORD_BCRYPT);
	}

}