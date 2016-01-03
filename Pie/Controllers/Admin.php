<?php namespace Controllers;

use Controllers\AbstractController;
use Model\Admin as ModelAdmin;
use Services\Authentication\Password;

class Admin extends AbstractController
{
	public function __construct()
	{
		parent::__construct(true);
	}

	public function login()
	{
		$this->view->setFile('Views/Admin/login.php');

		$this->view->addData('form', $this->forms->adminLoginForm());

		$this->view->load();
	}

	public function process()
	{
		$post = $this->view->post();

		$valid = $this->validator->string('Password', $post->password)
			->email('Email', $post->email)->check();

		$valid->isFail('/Admin/Login');

		$admin = new ModelAdmin();

		$login = $admin->findEmailPassword($post->email);

		if ($login->count() > 0) {
			$this->loginAdmin($post->password, $login->first()->password, $login->first()->id);
		}

		$this->redirect->to('/Admin/Login');
	}

	protected function loginAdmin($postPwd, $dbPwd, $adminId)
	{
		$password = new Password(PASSWORD_BCRYPT);	

		if ($password->verify($postPwd, $dbPwd)) {

			$this->auth->set('admin_id', (int) $adminId);

			$this->redirect->to('/Dashboard/Home');
		}
		
		$this->redirect->messages(
			$this->auth->getUserKey(), 
			['failure' => ['messages' => ['User Details Incorect']]]
		)->to('/Admin/Login');
	}

	public function createHash()
	{
		$password = new Password(PASSWORD_BCRYPT);

		if ($this->loader->isTest()) {
			echo $this->requests->get()->pwd . PHP_EOL;
			echo $password->generate($this->requests->get()->pwd);
			die();
		}
	}
}