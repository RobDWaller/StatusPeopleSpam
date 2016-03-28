<?php namespace Controllers;

use Controllers\AbstractController;
use Model\Admin as ModelAdmin;
use Services\Authentication\Password;
use Model\Login;
use Helpers\Ip;

class Admin extends AbstractController
{
	use Ip;

	protected $login;

	public function __construct()
	{
		parent::__construct(true);

		$this->login = new Login();
	}

	public function login()
	{	
		if ($this->auth->isAdminLoggedIn()) {
			$this->redirect->to('/Dashboard/Home');
		}

		$this->overrideViewData(false);

		$this->view->setFile('Views/Admin/login.php');

		$this->view->addData('form', $this->forms->adminLoginForm());

		$this->view->load();
	}

	public function process()
	{
		$this->checkLoginAttempts();

		$post = $this->view->post();

		$valid = $this->validator->csrf($post->csrf)->string('Password', $post->password)
			->email('Email', $post->email)->check();

		$valid->isFail('/Admin/Login');

		$admin = new ModelAdmin();

		$login = $admin->findEmailPassword($post->email);

		if ($login->count() > 0) {
			$this->loginAdmin($post->password, $login->first()->password, $login->first()->id);
		}

		$this->login->addLogin($this->ipAddress(), 0);

		$this->loginFail();
	}

	public function logout()
	{
		$this->auth->destroy();

		$this->redirect->to('/Admin/Login');
	}

	public function createHash()
	{
		$password = new Password(PASSWORD_BCRYPT);

		if ($this->loader->isTest()) {
			echo $this->view->get()->pwd . PHP_EOL;
			echo $password->generate($this->view->get()->pwd);
			die();
		}
	}

	protected function checkLoginAttempts()
	{
		$count = $this->login->countLogins($this->ipAddress());

		if ($count >= 5) {
			$this->loginFail();
		}
	}

	protected function loginFail()
	{
		$this->redirect->messages(
			$this->auth->getUserKey(), 
			['failure' => ['messages' => ['User Details Incorrect']]]
		)->to('/Admin/Login');
	}

	protected function loginAdmin($postPwd, $dbPwd, $adminId)
	{
		$password = new Password(PASSWORD_BCRYPT);	

		if ($password->verify($postPwd, $dbPwd)) {

			$this->auth->set('admin_id', (int) $adminId);

			$this->login->addLogin($this->ipAddress(), 1);

			$this->redirect->to('/Dashboard/Home');
		}
		
		$this->login->addLogin($this->ipAddress(), 0);

		$this->loginFail();
	}
}