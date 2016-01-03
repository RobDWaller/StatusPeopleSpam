<?php namespace Fakers;

use Services\Html\Lists as HtmlLists;

class Lists
{
	protected $lists; 

	public function __construct()
	{
		$this->lists = new HtmlLists();
	}

	public function menu()
	{
		$list = $this->lists->open();

		$links[] = $this->lists->a(
			$this->lists->span('&', ['class' => 'ico3']) . ' Website',
			['href' => "https://statuspeople.com"]
		);

		$links[] = $this->lists->a(
			$this->lists->span('%', ['class' => 'ico3']) . ' Blog',
			['href' => "https://blog.statuspeople.com"]
		);

		foreach ($links as $l) {
			$list->addItem($l);
		}

		return $list;
	}
}