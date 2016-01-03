<?php namespace Services\Html;

use Services\Html\Base;
use HtmlObject\Element;

class Generic extends Base
{
	public static function make($element, $value, array $attributes = [])
	{
		return Element::$element($value, $attributes);
	}

	public function clean()
	{

	}
}