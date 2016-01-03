<?php namespace Fakers;

use Services\Html\Lists as MainMenuList;
use Services\Authentication\Auth;

class Menu
{
	protected $auth;
	protected $list;

	public function __construct(Auth $auth, MainMenuList $list)
	{
		$this->auth = $auth;
		$this->list = $list;
	}

	public function make()
	{
		$this->list->open(['class' => 'fakericons']);

		if (!$this->auth->isLoggedIn()) {
			$this->loggedOut();
		} elseif ($this->auth->user()->type == 1) {
			$this->noSubscription();
		} else {
			$this->subscription();
		}

		return $this;
	}

	public function build()
	{
		return $this->list->build();
	}

	protected function loggedOut()
	{
		$this->createListElement('p', 'ico', 'Home', 'Home', '//fakers.statuspeople.com/');

		$this->help();

		$this->training();
		
		$this->createListElement('%', 'ico3', 'Blog', 'Blog', '//blog.statuspeople.co');
	}

	protected function noSubscription()
	{
		$this->createListElement('"', 'ico3', 'Fakers Dashboard', 'Dashboard', '/Fakers/Scores');

		$this->help();

		$this->training();
		
		$this->subscriptions();

		$this->settings();
	}

	protected function subscription()
	{
		$this->createListElement('"', 'ico3', 'Fakers Dashboard', 'Dashboard', '/Fakers/Dashboard');

		$this->createListElement('t', 'ico', 'Follower Analytics', 'Analytics', '/Fakers/Followers');

		$this->createListElement('s', 'ico', 'Search for Friends', 'Search', '');

		$this->help();

		$this->training();
		
		$this->subscriptions();

		$this->settings();
	}

	protected function help()
	{
		$this->createListElement('!', 'ico3', 'Help', 'Help', '/Fakers/Help');
	}

	protected function training()
	{
		$this->createListElement(')', 'ico3', 'Training', 'Training', '//statuspeople.com/Pages/Training');
	}

	protected function subscriptions()
	{
		$this->createListElement('$', '', 'Subscriptions', 'Subscriptions', '/Payments/Subscriptions');
	}

	protected function settings()
	{
		$this->createListElement('9', 'ico2', 'Settings', 'Settings', '/Fakers/Settings');
	}

	protected function createListElement($icon, $class, $tip, $name, $link, $linkId = null)
	{
		$linkArray = $linkId ? ['link' => $link, 'id' => $linkId] : ['href' => $link];

		$this->list->addItem($this->list->a($this->list->span($icon, ['class' => $class.' icon', 
			'data-tip' => $tip]).' '.$name, 
			$linkArray));
	}

}	