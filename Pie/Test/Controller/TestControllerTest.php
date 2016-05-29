<?php namespace Test\Controller;

use Test\Build;
use Fakers\ViewData;
use Services\Authentication\Auth;

class TestControllerTest extends Build
{
	public function testGetMenuLoggedOut()
	{
		$view = new ViewData();
		
		$list = $view->getMenu()->build();

		$this->assertInstanceOf('HtmlObject\Element', $list);

		$this->visit('/Test/Menu')->seePageIs('/Test/Menu')
			->see('Home')
			->see('Help')
			->see('Training')
			->see('Blog');
	}

	public function testGetMenuNoSubscription()
	{
		$view = new ViewData();
		$auth = new Auth();

		$auth->login(1, 1, 1);

		$list = $view->getMenu()->build();

		$this->assertInstanceOf('HtmlObject\Element', $list);

		$this->login(1, 1, 1, '/Test/Menu')
			->see('Dashboard')
			->see('Help')
			->see('Training')
			->see('Subscriptions')
			->see('Settings');
	}

	public function testGetMenuSubscription()
	{
		$view = new ViewData();
		$auth = new Auth();

		$auth->login(1, 1, 2);

		$list = $view->getMenu()->build();

		$this->assertInstanceOf('HtmlObject\Element', $list);

		$this->login(1, 1, 2, '/Test/Menu')
			->see('Dashboard')
			->see('Help')
			->see('Analytics')
			->see('Search')
			->see('Training')
			->see('Subscriptions')
			->see('Settings');
	}

	public function testGetAccountForm()
	{
		$view = new ViewData();

		$selects = $view->getAccountForm()->build();

		$this->assertInstanceOf('HtmlObject\Element', $selects);

		$this->visit('/Test/AccountForm')
			->see('form');
	}

	public function testGetLinksMenuLoggedOut()
	{
		$view = new ViewData();
		
		$list = $view->getLinksMenu()->build();

		$this->assertInstanceOf('HtmlObject\Element', $list);

		$this->visit('/Test/LinksMenu')
			->see('Find Out More')
			->see('Terms')
			->see('Help');
	}

	public function testGetLinksMenuLoggedIn()
	{
		$view = new ViewData();
		$auth = new Auth();
		
		$auth->login(1, 1, 1);

		$list = $view->getLinksMenu()->build();

		$this->assertInstanceOf('HtmlObject\Element', $list);

		$this->login(1, 1, 1, '/Test/LinksMenu')
			->see('Find Out More')
			->see('Terms')
			->see('Help')
			->see('Reset Twitter')
			->see('Dashboard')
			->see('Subscriptions')
			->see('Settings');
	}

	public function testGetLinksMenuSubscription()
	{
		$view = new ViewData();
		$auth = new Auth();
		
		$auth->login(1, 1, 2);

		$list = $view->getLinksMenu()->build();

		$this->assertInstanceOf('HtmlObject\Element', $list);

		$this->login(1, 1, 2, '/Test/LinksMenu')
			->see('Find Out More')
			->see('Terms')
			->see('Help')
			->see('Reset Twitter')
			->see('Dashboard')
			->see('Subscriptions')
			->see('Settings')
			->see('Analytics');
	}

	public function testGetHiddenFields()
	{
		$view = new ViewData();
		
		$fields = $view->getHiddenFields()->build();

		$this->assertInstanceOf('HtmlObject\Element', $fields);

		$this->visit('/Test/HiddenFields')
			->see('twitterhandle')
			->see('twitterid')
			->see('spamscore')
			->see('spam')
			->see('potential')
			->see('checks')
			->see('followers')
			->see('firsttime')
			->see('accounttype');
	}
}