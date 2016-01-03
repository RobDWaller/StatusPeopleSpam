<?php namespace Fakers;

use Services\Html\Generic;

class Admin
{
	public function link()
	{
		return Generic::make('a', 'Dashboard', ['href' => '/Dashboard/Home']);
	}
}