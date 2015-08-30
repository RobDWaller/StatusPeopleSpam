<?php namespace Services\Html;

use HtmlObject\Traits\Tag;
use Services\Html\Html as HtmlInterface;
use HtmlObject\Element;

abstract class Base extends Tag implements HtmlInterface
{
	public function __call($name, $attributes)
	{
		$element = isset($attributes[0]) ? Element::$name($attributes[0]) : Element::$name();

		if (isset($attributes[1])) {
			$this->addAttributes($element, $attributes[1]);
		}

		return $element;
	}

	public function addAttributes($element, array $attributes = null)
	{
		if ($attributes != null)
		{
			foreach ($attributes as $key => $value) {
				$element->$key = $value;
			}
		}
	}
}