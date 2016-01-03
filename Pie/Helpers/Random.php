<?php namespace Helpers;

trait Random
{
	public function rand()
	{
		return mt_rand(1, 99999);
	}

	public function numKey($salt)
	{
		return base_convert($salt . mt_rand(999, 999999), 36, 10);
	}
}