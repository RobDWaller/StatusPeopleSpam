<?php

use Controllers\AbstractController;

class Payments extends AbstractController
{
    protected $fakers;
    
    public function __construct()
    {
        parent::__construct();

        $this->fakers = new Fakers();
    }

    public function Details()
    {
        
        $this->auth->isLogin();
        
        $count = $this->payments->CountUserDetails($_SESSION['userid']);
        
		$validity = $this->fakers->_CheckValidity($_SESSION['userid']);
		$data['twitterid'] = $this->validation->ObscureNumber($_SESSION['userid'],SALT_ONE);
		
		if (!$validity[0])
		{
			$data['logout'] = 1;
			$data['menu'] = '&nbsp;';
			
			if ($_SESSION['userid']<1)
			{
				$data['logout'] = 2;
				$data['menu'] = $this->fakers->_BuildMenu();
			}
		}
		
		if ($count==0)
        {
            $fields = array('email'=>array('Email','Text','',$_SESSION['email']),
                            'title'=>array('Title','Title','',$_SESSION['title']),
                            'firstname'=>array('First Name','Text','',$_SESSION['firstname']),
                            'lastname'=>array('Last Name','Text','',$_SESSION['lastname']),
                            'submit'=>array('Proceed','Submit')); 
            
            $data['form'] = $this->forms->FormBuilder('yourdetailsform',$this->routechutney->BuildUrl('/Payments/ProcessDetails',$this->mod_rewrite),$fields);

            if (isset($_SESSION['message']))
            {
                $data['message'] = $_SESSION['message'];
            }
            elseif ($vars[0]==1) 
            {
                $data['message'] = $this->build->PageMessage('alert',array('Please connect to you Twitter account to make use of this service.'));
            }
            
			$data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
            $data['title'] = 'Your Details : Fakers App from StatusPeople.com';
			$data['menu'] = '&nbsp;';
            
            $this->sessions->UnsetSessions(array('message','email','title','firstname','lastname'));
            
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->fakers->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
            $this->glaze->view('Payments/details.php',$data);
        } 
        else
        {
            $this->sessions->UnsetSessions(array('message','email','title','firstname','lastname'));
            
            header('Location:'.$this->routechutney->BuildUrl('/Payments/Subscriptions',$this->mod_rewrite));
            die();
        }    
        
    }
    
    public function ProcessDetails()
    {
        
        $this->auth->isLogin();
        
        $count = $this->payments->CountUserDetails($_SESSION['userid']);
        
        if ($count==0)
        {
        
            $valid[] = $this->validation->ValidateEmail($_POST['email']);
            $valid[] = $this->validation->ValidateString($_POST['title'],'Title'); 
            $valid[] = $this->validation->ValidateString($_POST['firstname'],'First Name'); 
            $valid[] = $this->validation->ValidateString($_POST['lastname'],'Last Name'); 

            $isvalid = true;
            $messages = array();

            foreach ($valid as $v)
            {
                if (!$v[0])
                {
                    $isvalid = false;
                    $messages[] = $v[1];
                }
            }

            if ($isvalid)
            {
                $adddetails = $this->payments->AddUserDetails($_SESSION['userid'],$_POST['email'],$_POST['title'],$_POST['firstname'],$_POST['lastname'],time());

                if ($adddetails>0)
                {
                    header('Location:'.$this->routechutney->BuildUrl('/Payments/Subscriptions',$this->mod_rewrite));
                }
                else
                {
                    $_SESSION['message'] = $this->build->PageMessage('failure',array('Failed to add user details to system. Please try again. If this problem persists please contact info@statuspeople.com.'));
                    $_SESSION['email'] = $_POST['email'];
                    $_SESSION['firstname'] = $_POST['firstname'];
                    $_SESSION['lastname'] = $_POST['lastname'];
                    $_SESSION['title'] = $_POST['title'];

                    header('Location:'.$this->routechutney->BuildUrl('/Payments/Details',$this->mod_rewrite));
                }

            }
            else {
                $_SESSION['message'] = $this->build->PageMessage('alert',$messages);
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['firstname'] = $_POST['firstname'];
                $_SESSION['lastname'] = $_POST['lastname'];
                $_SESSION['title'] = $_POST['title'];

                header('Location:'.$this->routechutney->BuildUrl('/Payments/Details',$this->mod_rewrite));
            }
        }
        else 
        {
            $this->sessions->UnsetSessions(array('message','email','title','firstname','lastname'));
            
            header('Location:'.$this->routechutney->BuildUrl('/Payments/Subscriptions',$this->mod_rewrite));
            die();
        }
    }
    
	public function UpdateDetails()
	{
		$this->auth->isLogin();
        
		$valid[] = $this->validation->ValidateEmail($_POST['email']);
		$valid[] = $this->validation->ValidateString($_POST['title'],'Title'); 
		$valid[] = $this->validation->ValidateString($_POST['firstname'],'First Name'); 
		$valid[] = $this->validation->ValidateString($_POST['lastname'],'Last Name'); 
		
		$isvalid = true;
		$messages = array();
		
		foreach ($valid as $v)
		{
			if (!$v[0])
			{
				$isvalid = false;
				$messages[] = $v[1];
			}
		}
		
		if ($isvalid)
		{
		
			$count = $this->payments->CountUserDetails($_SESSION['userid']);
			
			if ($count==0)
			{
				
				$adddetails = $this->payments->AddUserDetails($_SESSION['userid'],$_POST['email'],$_POST['title'],$_POST['firstname'],$_POST['lastname'],time());
				
				if ($adddetails>0)
				{
					$_SESSION['message'] = $this->build->PageMessage('success',array('User details successfully added.'));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
				}
				else
				{
					$_SESSION['message'] = $this->build->PageMessage('failure',array('Failed to add user details to system. Please try again. If this problem persists please contact info@statuspeople.com.'));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
				}
	
			}
			else 
			{
				//$this->errorschutney->DebugArray($_POST);
				
				$updatedetails = $this->payments->UpdateUserDetails($_SESSION['userid'],$_POST['email'],$_POST['title'],$_POST['firstname'],$_POST['lastname']);
				
				if ($updatedetails>0)
				{
					$_SESSION['message'] = $this->build->PageMessage('success',array('User details successfully updated.'));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
				}
				else
				{
					$_SESSION['message'] = $this->build->PageMessage('failure',array('Failed to update user details to system. Please try again. If this problem persists please contact info@statuspeople.com.'));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
				}
			}
		}
		else {
			$_SESSION['message'] = $this->build->PageMessage('alert',$messages);
			header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
		}
	}
	
    public function Subscriptions($vars)
    {
        $this->auth->isLogin();
        
		$count = $this->payments->CountUserDetails($_SESSION['userid']);
		
		if ($count==0)
		{
			header('Location:'.$this->routechutney->BuildUrl('/Payments/Details',$this->mod_rewrite));
			die();
		}
		
        $userdetails = $this->payments->GetUserDetails($_SESSION['userid']);
        
//        $this->errorschutney->DebugArray($userdetails);
        
		$validity = $this->fakers->_CheckValidity($_SESSION['userid']);
		$data['twitterid'] = $this->validation->ObscureNumber($_SESSION['userid'],SALT_ONE);
		$data['title'] = 'Purchase A Subscription : Fakers App from StatusPeople.com';
		
		if (!$validity[0])
		{
			$data['logout'] = 1;
			$data['menu'] = '&nbsp;';
			
			if ($_SESSION['userid']<1)
			{
				$data['logout'] = 2;
				$data['menu'] = $this->fakers->_BuildMenu();
			}
		}
		
        if (isset($_SESSION['message']))
        {
            $data['message'] = $_SESSION['message'];
        }
        
        $fields = array('cmd'=>array('','Hidden','','_hosted-payment'),
                        'subtotal'=>array('','Hidden','','3.49'),
                        'buyer_email'=>array('','Hidden','',$userdetails[2]),
                        'first_name'=>array('','Hidden','',$userdetails[4]),
                        'last_name'=>array('','Hidden','',$userdetails[5]),
						'account_type'=>array('Type','Dropdown',array(array('Basic','Basic','Basic',1),array('Premium','Premium','Premium',2),array('Agency','Agency','Agency',3)),'','','Basic'),
                        'currency_code'=>array('Currency','Dropdown',array(array('GBP','GBP','GB Pound Sterling','GBP'),array('USD','USD','US Dollar','USD'),array('EUR','EUR','EU Euro','EUR')),'','','GBP'),
                        'period'=>array('Period','Dropdown',array(array('1','1','1 Month','1'),array('6','6','6 Months','6'),array('12','12','12 Months','12')),'','','GBP'),
                        'tax'=>array('','Hidden','','0.70'),
                        'saving'=>array('','Hidden','','0.00'),
                        'months'=>array('','Hidden','',1),
                        'checkout'=>array('Check Out','Submit')); 
        
        $data['form'] = $this->forms->FormBuilder('payform',$this->routechutney->BuildUrl('/Payments/Checkout',$this->mod_rewrite),$fields);
        
        $data['currency'] = '&pound;';
        $data['subtotal'] = '3.49';
        $data['tax'] = '0.70';
        $data['total'] = $data['subtotal'] + $data['tax'];
        $data['saving'] = '0.00';
        $data['months'] = '1';
        $data['type'] = isset($vars['type']) ? $vars['type'] : '';
		$data['homelink'] = $this->routechutney->HREF('/Fakers/Dashboard',$this->mod_rewrite);
		$data['menu'] = '&nbsp;';
        $data['message'] = false;
		
        $vc = $this->payments->CountValidRecords($_SESSION['userid']);
        
        if ($vc)
        {
            $vd = $this->payments->GetValidDate($_SESSION['userid']);
            
            $diff = $vd[0] - time();
            
            $days = round($diff/86400,0,PHP_ROUND_HALF_UP);
            
			$message = 'Your Fakers Dashboard Subscription will expire in '.$days.' day(s).';
			
			if ($days < 0)
			{
				$message = 'Your Fakers Dashboard Subscription expired '.$days.' day(s) ago.';
			}
			
            $data['message'] .= $this->build->PageMessage('info',array($message));
        }
        
        $this->sessions->UnsetSessions(array('message'));
		
		if ($_SESSION['type']>=1)
		{
			$data['accountform'] = $this->fakers->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
		}
            
        $this->glaze->view('Payments/subscriptions.php',$data);

    }
    
    public function Checkout()
    {
        $this->auth->isLogin();
        
        $userid = $_SESSION['userid'];
        
		$validity = $this->fakers->_CheckValidity($_SESSION['userid']);
		$data['twitterid'] = $this->validation->ObscureNumber($_SESSION['userid'],SALT_ONE);
		$data['title'] = 'Checkout : Fakers App from StatusPeople.com';
		
		if (!$validity[0])
		{
			$data['logout'] = 1;
			$data['menu'] = '&nbsp;';
			
			if ($_SESSION['userid']<1)
			{
				$data['logout'] = 2;
				$data['menu'] = $this->fakers->_BuildMenu();
			}
		}
		
        $transactionid = substr($this->validation->HashString($userid.time().rand(1,9999)),0,12);
            
        setcookie('transactionid',$transactionid,time()+3600);
        
        $create = $this->payments->CreatePurchase($userid,$transactionid,$_POST['currency_code'],$_POST['subtotal'],$_POST['account_type'],time());
        
        if ($create)
        {
        
            $fields = array('cmd'=>array('','Hidden','','_hosted-payment'),
                            'subtotal'=>array('','Hidden','',$_POST['subtotal']),
                            'business'=>array('','Hidden','',PAYPAL_ID),
                            'paymentaction'=>array('','Hidden','','sale'),
                            'return'=>array('','Hidden','',$this->routechutney->HREF('/Payments/Confirmation',$this->mod_rewrite)),
                            'cancel_return'=>array('','Hidden','',$this->routechutney->HREF('/Payments/Cancelation',$this->mod_rewrite)),
                            'notify_url'=>array('','Hidden','',$this->routechutney->HREF('/Payments/Confirmation',$this->mod_rewrite)),
                            'buyer_email'=>array('','Hidden','',$_POST['buyer_email']),
                            'first_name'=>array('','Hidden','',$_POST['first_name']),
                            'last_name'=>array('','Hidden','',$_POST['last_name']),
                            'currency_code'=>array('','Hidden','',$_POST['currency_code']),
                            'period'=>array('','Hidden','',$_POST['period']),
                            'tax'=>array('','Hidden','',$_POST['tax']),
                            'METHOD'=>array('Pay','Submit')); 

            $data['form'] = $this->forms->FormBuilder('payform',PAYPAL_ACTION,$fields);

            setcookie('subtotal',$_POST['subtotal'],time()+3600);
            setcookie('email',$_POST['buyer_email'],time()+3600);
            setcookie('firstname',$_POST['first_name'],time()+3600);
            setcookie('currency',$_POST['currency_code'],time()+3600);
            setcookie('months',$_POST['period'],time()+3600);
            setcookie('saving',$_POST['saving'],time()+3600);
            setcookie('tax',$_POST['tax'],time()+3600);
			setcookie('type',$_POST['account_type'],time()+3600);
            
            $currency = '&pound;';
            
            if ($_POST['currency_code']=='USD')
            {
                $currency = '&#36;';
            }
            elseif ($_POST['currency_code']=='EUR')
            {
                $currency = '&euro;';
            }
            
            $data['currency'] = $currency;
			$data['type'] = ($_POST['account_type']==1?'Basic':'Premium');
            $data['subtotal'] = $_POST['subtotal'];
            $data['tax'] = $_POST['tax'];
            $data['total'] = $_POST['subtotal']+$_POST['tax'];
            $data['saving'] = $_POST['saving'];
            $data['months'] = $_POST['months'];
			$data['homelink'] = $this->routechutney->HREF('/Fakers/Dashboard',$this->mod_rewrite);
            $data['menu'] = '&nbsp;';
			
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->fakers->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
            $this->glaze->view('Payments/checkout.php',$data);
        
        }
        else
        {
            $_SESSION['message'] = $this->build->PageMessage('failure',array('Incorect payment details, unable to process checkout. Please try again. If this problem persists please contact info@statuspeople.com'));
            header('Location:'.$this->routechutney->BuildUrl('/Payments/Subscriptions',$this->mod_rewrite));
            die();
        }
    }
    
    public function Confirmation()
    {
        $this->auth->isLogin();
        
        $transactionid = $_COOKIE['transactionid'];
        $email = $_COOKIE['email'];
        $firstname = $_COOKIE['firstname'];
        
        $transaction = $this->payments->CountPurchases($transactionid);
		$data['title'] = 'Purchase Confirmation : Fakers App from StatusPeople.com';
        
        if ($transaction)
        {
            
			$data['type'] = 'Basic'; 
			
			if ($_COOKIE['type']==2)
			{
				$data['type'] = 'Premium'; 
			}
			else if ($_COOKIE['type']==3)
			{
				$data['type'] = 'Agency';
			}
			
			$_SESSION['type'] = $_COOKIE['type'];
            $data['months'] = $_COOKIE['months'];
            $data['subtotal'] = $_COOKIE['subtotal'];
            $data['saving'] = $_COOKIE['saving'];
            $data['tax'] = $_COOKIE['tax'];
            $data['currency'] = $_COOKIE['currency'];
            $data['total'] = $data['subtotal']+$data['tax'];
            $data['transactionid'] = $transactionid;
            
            $purchase = $this->payments->GetPurchaseDetails($transactionid);
            
			//$this->errorschutney->DebugArray($purchase);
			
            if (!$purchase[6])
            {
                $this->payments->CompletePurchase($transactionid);

                $m = 'months';

                if ($data['months']==1)
                {
                    $m = 'month';
                }

                $vc = $this->payments->CountValidRecords($purchase[1]);

//                $this->errorschutney->PrintArray($purchase);
//                $this->errorschutney->PrintArray($vc);
                
                if ($vc)
                {
                    $getlastvalid = $this->payments->GetValidDate($purchase[1]);
                    
					if ($getlastvalid[0]<=time())
					{
						$lastvalid = date('Y/m/d',time());
					}
					else
					{
						$lastvalid = date('Y/m/d',$getlastvalid[0]);	
					}
                    
                    $valid = strtotime($lastvalid.' +'.$data['months'].' '.$m);
                    
//                    $this->errorschutney->DebugArray(date('y/m/d',$valid));
                    
                    $this->payments->UpdateValidDate($purchase[0],$purchase[1],$valid);
                }
                else
                {
                    $valid = strtotime('+'.$data['months'].' '.$m);
                    $this->payments->CreateValidDate($purchase[0],$purchase[1],$valid);
                }

                $this->_SendPurchaseEmail($email, $firstname, $data);    
            }
            
			$validity = $this->fakers->_CheckValidity($_SESSION['userid']);
			$data['twitterid'] = $this->validation->ObscureNumber($_SESSION['userid'],SALT_ONE);
			
			if (!$validity[0])
			{
				$data['logout'] = 1;
				$data['menu'] = '&nbsp;';
				
				if ($_SESSION['userid']<1)
				{
					$data['logout'] = 2;
					$data['menu'] = $this->fakers->_BuildMenu();
				}
			}
			
			$data['homelink'] = $this->routechutney->HREF('/Fakers/Dashboard',$this->mod_rewrite);
			$data['menu'] = '&nbsp;';
			
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->fakers->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
            $this->glaze->view('Payments/confirmation.php',$data);
        }
        else
        {
            $_SESSION['message'] = $this->build->PageMessage('failure',array('Purchase confirmation process failed please contact info@statuspeople.com'));
            header('Location:'.$this->routechutney->BuildUrl('/Payments/Subscriptions',$this->mod_rewrite));
            die();
        }
        
        
    }
    
    public function Cancelation()
    {
        $this->auth->isLogin();
        
		$validity = $this->fakers->_CheckValidity($_SESSION['userid']);
		$data['twitterid'] = $this->validation->ObscureNumber($_SESSION['userid'],SALT_ONE);
		$data['title'] = 'Purchase Cancellation : Fakers App from StatusPeople.com';
		
		if (!$validity[0])
		{
			$data['logout'] = 1;
			$data['menu'] = '&nbsp;';
			
			if ($_SESSION['userid']<1)
			{
				$data['logout'] = 2;
				$data['menu'] = $this->fakers->_BuildMenu();
			}
		}
		
        $this->glaze->view('Payments/cancellation.php');
    }
    
    protected function _SendPurchaseEmail($email,$firstname,$data)
    {
        $headers['from'] = 'StatusPeople <fakers@statuspeople.com>';
        $headers['reply'] = 'fakers@statuspeople.com';
        $headers['return'] = 'fakers@statuspeople.com';

        $message = '<p>Dear '.$firstname.',</p>';
        $message .= '<p>Thank you for purchasing our Fakers Dashboard. Your purchase details are below and remember to keep your purchase id safe.</p>';
        $message .= '<ul>';
        $message .= '<li>Purchase ID: '.$data['transactionid'].'</li>';
        $message .= '<li>Account Type: '.$data['type'].'</li>';
        $message .= '<li>Subscription Period: '.$data['months'].' month(s)</li>';
        $message .= '<li>Sub-Total: '.$data['currency'].' '.$data['subtotal'].'</li>';
        $message .= '<li>Tax: '.$data['currency'].' '.$data['tax'].'</li>';
        $message .= '<li>Total: '.$data['currency'].' '.$data['total'].'</li>';
        $message .= '</ul>';
        $message .= '<p>If you have any problems with your purchase please email us at info@statuspeople.com quoting your Purchase ID.</p>';
        $message .= '<p>Thanks, The StatusPeople Team</p>';

        Email::SendEmail($email,'Thank You for Purchasing StatusPeople Fakers Dashboard',$message,$headers);

    }
    
}

?>