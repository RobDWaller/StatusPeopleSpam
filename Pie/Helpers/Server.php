<?php namespace Helpers;

trait Server
{

	public function getServer()
	{
		return (object) $_SERVER;
	}

}