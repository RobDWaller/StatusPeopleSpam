<?php namespace Controllers;

use Controllers\AbstractController;
use Model\User;
use Fakers\Forms;

class Dashboard extends AbstractController
{
	protected $user;

	protected $form;

	public function __construct()
	{
		parent::__construct(true);

		$this->isAdminLogin();

		$this->user = new User;

		$this->form = new Forms;
	}

	public function home()
	{
		$this->view->addData('newUsers', $this->user->findNewUsers(20));
		$this->view->addData('hash', $this->hash);
		$this->view->addData('form', $this->form->postHandleForm('/Accounts/Search'));
		
		$this->view->setFile('Views/Dashboard/home.php');
		$this->view->load();
	}
}