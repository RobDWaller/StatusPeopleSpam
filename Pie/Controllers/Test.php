<?php namespace Controllers;

use Fakers\ViewData;
use Services\Routes\View;
use Services\Authentication\Auth;

class Test
{
	protected $viewData;
	protected $view;
	protected $auth;

	public function __construct()
	{
		$this->viewData = new ViewData;
		$this->view = new View;
		$this->auth = new Auth;
	}

	protected function login()
	{
		if (isset($this->view->get()->id) && isset($this->view->get()->pid) 
			&& isset($this->view->get()->t)) {

			$this->auth->login(
				$this->view->get()->id,
				$this->view->get()->pid,
				$this->view->get()->t
			);
		
		}
	}

	public function menu()
	{
		$this->login();

		echo $this->viewData->getMenu()->build();

		die();
	}

	public function linksMenu()
	{
		$this->login();

		echo $this->viewData->getLinksMenu()->build();

		die();
	}

	public function accountForm()
	{
		$this->login();

		echo $this->viewData->getAccountForm()->build();

		die();
	}

	public function hiddenFields()
	{
		echo $this->viewData->getHiddenFields()->build();

		die();
	}
}