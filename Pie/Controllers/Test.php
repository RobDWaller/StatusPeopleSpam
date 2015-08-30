<?php namespace Controllers;

use Controllers\AbstractController;
use Services\Routes\Loader;
use Services\Routes\Redirector;
use Services\Authentication\Session;

class Test extends AbstractController
{
	protected $loader;
	protected $redirect;

	function __construct()
	{
		
		parent::__construct();

		$this->loader = new Loader;
		$this->redirect = new Redirector;
		$this->session = new Session;
		$this->payments = new \PaymentRequests;
		
	}

	public function Loader()
    {
    	$data['title'] = 'Maintenance Page';
		$data['homelink'] = '/';
		
		$this->session->destroy();
		$this->session->destroyCookies();

		if ($this->loader->isDown()) {
			$this->glaze->view('Spam/maintenance.php',$data);
		}

		$data['form'] = $this->form->postHandleForm('/Test/LoadAccount');

		$this->glaze->view('Spam/test.php',$data);
    }
    
    public function LoadAccount()
    {
    	if ($this->loader->isTest()) {

        	$account = $this->dbbind->GetUserInfoByScreenName($this->requests->post()->handle);
        	$validdate = $this->payments->GetValidDate($account[1]); 
        	
        	$this->auth->login($account[1],$validdate[1]);

        	$this->redirect->to('/Fakers/Scores');
        }

        $this->redirect->to('/');
    }
}