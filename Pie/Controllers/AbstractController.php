<?php namespace Controllers;

use Services\Routes\Loader;
use Services\Authentication\Auth;
use Fakers\Forms;
use Services\Validation\Validator;
use Services\Routes\View;
use Fakers\ViewData;
use Helpers\Dir;
use Services\Routes\Redirector;
use Helpers\Key;
use Helpers\Messages;
use Services\Hasher\Hash;

abstract class AbstractController
{
	use Dir, Key, Messages;

	protected $loader;

	protected $auth;

	protected $forms;

	protected $validator;

	protected $view; 

	protected $redirect;

	protected $hash;

	public function __construct($admin = false)
	{
		$this->loader = new Loader();
		$this->auth = new Auth();
		$this->forms = new Forms();
		$this->validator = new Validator();
		$this->view = new View(); 
		$this->redirect = new Redirector();
		$this->hash = new Hash();

		$this->generateKey($this->auth, 'user');

		$this->viewData($admin);
		$this->setMessages();
	}

	protected function viewData($admin)
	{
		$viewData = new ViewData($admin);

		$this->view->addData('metaData', $viewData->getMetaData());
		$this->view->addData('homeLink', $viewData->getHomeLink());
		$this->view->addData('logo', $viewData->getLogo());
		$this->view->addData('menu', $viewData->getMenu());
		$this->view->addData('accountForm', $viewData->getAccountForm());
		$this->view->addData('hiddenFields', $viewData->getHiddenFields());
		$this->view->addData('footerDate', $viewData->getFooterDate());
		$this->view->addData('linksMenu', $viewData->getLinksMenu());
		$this->view->addData('headerPath', $viewData->getHeaderUrl());
		$this->view->addData('footerPath', $viewData->getFooterUrl());
	}

	protected function setMessages()
	{
		$this->view->addData('messages', $this->buildMessages($this->redirect));
	}

	public function isLogin()
	{
		if (!$this->auth->isLoggedIn()) {
			$this->redirect->messages(
				$this->auth->getUserKey(), 
				['alert' => ['messages' => ['Please connect to your Twitter account to access the Fakers App.']]]
			)->to('/');
		}
	}

	public function isAdminLogin()
	{
		if (!$this->auth->isAdminLoggedIn()) {
			$this->redirect->messages(
				$this->auth->getUserKey(), 
				['failure' => ['messages' => ['You are not able to access this content, please log in first.']]]
			)->to('/Admin/Login');
		}
	}
}