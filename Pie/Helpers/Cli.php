<?php namespace Helpers;

trait Cli
{
	public function hasArguments($arguments)
    {
        return !empty($arguments[1]) && !empty($arguments[2]);
    }
}