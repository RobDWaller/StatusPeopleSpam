<?php namespace Controllers;

use Controllers\AbstractController;
use Model\User;
use Fakers\Forms;
use Model\Fake;

class Dashboard extends AbstractController
{
	protected $user;

	protected $form;

	protected $fake;

	public function __construct()
	{
		parent::__construct(true);

		$this->isAdminLogin();

		$this->user = new User;

		$this->form = new Forms;

		$this->fake = new Fake;
	}

	public function home()
	{
		$this->view->addData('newUsers', $this->user->findNewUsers(10));
		$this->view->addData('block_month_count', $this->fake->monthBlockCount());
		$this->view->addData('block_week_count', $this->fake->weekBlockCount());
		$this->view->addData('blocks', $this->fake->findNewBlocks(7));
		$this->view->addData('hash', $this->hash);
		$this->view->addData('form', $this->form->postHandleForm('/Accounts/Search'));
		
		$this->view->setFile('Views/Dashboard/home.php');
		$this->view->load();
	}
}