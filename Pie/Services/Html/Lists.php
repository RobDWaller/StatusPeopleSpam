<?php namespace Services\Html;

use Services\Html\Base;
use HtmlObject\Element;

class Lists extends Base
{
	protected $list;
	protected $items;

	public function open(array $attributes = null)
	{
		$this->list = Element::ul();
		$this->addAttributes($this->list, $attributes);

		return $this;
	}

	public function addItem($value, array $attributes = null)
	{
		$item = Element::li($value);
		$this->addAttributes($item, $attributes);

		$this->items[] = $item;

		return $this;
	}

	public function build()
	{
		return $this->list->nest($this->items);
	}

	public function clean()
	{
		$this->list = null;
		$this->items = null;
	}
}