<?php namespace Services\Validation;

use Services\Validation\Validator;

class Facade
{
	public static function make()
	{
		return new Validator();
	}
}