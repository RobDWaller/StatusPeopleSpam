<?php namespace Controllers;

use Controllers\AbstractController;
use Model\UserInfo;
use Model\Valid;
use Model\User;
use Model\Purchase;
use Fakers\Forms;

class Account extends AbstractController
{
	protected $userInfo;

	protected $valid;

    protected $user;

    protected $purchase;

    protected $form;

	function __construct()
	{
		parent::__construct(true);
		
        $this->isAdminLogin();

		$this->userInfo = new UserInfo;

        $this->user = new User;

        $this->purchase = new Purchase;

		$this->valid = new Valid;	

        $this->form = new Forms;
	}

	public function User()
    {
        if (isset($this->view->get()->id)) {
            $this->buildUserDetails($this->hash->decode($this->view->get()->id));
        }

        $this->noUserCredentials();
    }

    public function Search()
    {
        $valid = $this->validator->csrf($this->view->post()->csrf)
            ->string('Twitter Handle', $this->view->post()->handle)->check();

        $valid->isFail('/Dashboard/Home');

        $user = $this->userInfo->findScreenName($this->view->post()->handle);

        if ($user->count()) {
            $this->redirect->to('/Account/User?id=' . $this->hash->encode((int) $user->first()->twitterid));
        }

        $this->redirect->messages(
            $this->auth->getUserKey(), 
            ['alert' => ['messages' => ['No User Name Found']]]
        )->to('/Dashboard/Home');
    }
    
    protected function noUserCredentials()
    {
        $this->redirect->messages(
            $this->auth->getUserKey(), 
            ['failure' => ['messages' => ['No User Credentials Set']]]
        )->to('/Dashboard/Home');
    }

    protected function buildUserDetails($id)
    {
        $user = $this->user->findUserDetails($id);

        if ($user->count()) {
            $this->view->addData('title', $user->screen_name.' Details Page');

            $this->view->addData('user', $user);
            $this->view->addData('purchases', $this->purchase->findUserPurchases($user->first()->twitterid));
            $this->view->addData('hash', $this->hash);
            $this->view->addData('form', $this->form->adminPaymentForm('/Payment/Add', 'Add Payment', $this->hash->encode($user->first()->twitterid)));
            
            $this->view->setFile('Views/Accounts/user.php');
            
            $this->view->load();
        }

        $this->redirect->messages(
            $this->auth->getUserKey(), 
            ['alert' => ['messages' => ['No User Details Found']]]
        )->to('/Dashboard/Home');
    }

    public function Loader()
    {
    	if (isset($this->view->get()->id)) {
        
            $id = $this->hash->decode($this->view->get()->id);

            if ($id) {

                $user = $this->user->findUserDetails($id);

                $valid = $this->valid->findAccoutType($user->first()->twitterid); 
            	
            	if ($user->count()) {
            	 	$this->auth->login(
                        $user->first()->twitterid, 
                        $user->first()->twitterid,
                        $this->auth->processType($valid->first())
                    );

            	 	$this->redirect->to('/Fakers/Scores');	
            	}

                $this->accountLoadFail('Something Went Wrong, Could Not Access User Account');
		    }

            $this->accountLoadFail('Something Went Wrong, Invalid User');
		}

        $this->accountLoadFail('Something Went Wrong, Could Not Find User Account');    
    }

    protected function accountLoadFail($message)
    {
        $this->redirect->messages(
                $this->auth->getUserKey(), 
                ['failure' => ['messages' => [$message]]]
            )
            ->to('/Dashboard/Home');
    }
}