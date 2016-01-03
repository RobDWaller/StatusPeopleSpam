<?php namespace Helpers;

trait Serialize
{
	public function serialize($value)
	{
		return serialize($value);
	}	

	public function unserialize($string)
	{
		return unserialize($string);
	}
}