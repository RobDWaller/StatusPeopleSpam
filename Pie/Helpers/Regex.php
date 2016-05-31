<?php namespace Helpers;

trait Regex
{
	public function stringContainsLink($string)
	{
		preg_match('/http:\/\/|https:\/\//i', $string, $match);
		
		return !$match ? false : true; 
	}
}