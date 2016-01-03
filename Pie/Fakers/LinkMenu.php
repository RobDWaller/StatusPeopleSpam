<?php namespace Fakers;

use Services\Html\Lists as HtmlList;
use Services\Authentication\Auth;

class LinkMenu
{
	protected $auth;
	protected $list;

	public function __construct(Auth $auth, HtmlList $list)
	{
		$this->auth = $auth;
		$this->list = $list;
	}

	public function make()
	{
		$this->list->open(['class' => 'linksmenu']);

		$this->standardLinks();
		$this->loggedInLinks();

		return $this;
	}

	public function build()
	{
		return $this->list->build();
	}

	protected function standardLinks()
	{
		$this->createElement('Find Out More', '/Fakers/FindOutMore/');
		$this->createElement('Terms', '/Fakers/Terms/');
		$this->createElement('Help', '/Fakers/Help/');
	}

	protected function loggedInLinks()
	{
		if ($this->auth->isLoggedIn()) {
			$this->createElement('Reset Twitter', '/Fakers/Reset/');
			$this->createElement('Dashboard', '/Fakers/Scores/');
			$this->createElement('Subscriptions', '/Payments/Subscriptions/');
			$this->createElement('Settings', '/Fakers/Settings/');
			$this->subscriptionLinks();
		}
	}

	protected function subscriptionLinks()
	{
		if ($this->auth->user()->type == 2) {
			$this->createElement('Analytics', '/Fakers/Followers');
		}
	}

	protected function createElement($value, $link)
	{
		$this->list->addItem(
			$this->list->a($value, ['href' => $link])
		);
	}

}	