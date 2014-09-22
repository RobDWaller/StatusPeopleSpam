<?php

/*
 * property DBRequests $dbbind 
 */

class Fakers extends Jelly
{

	# Header #

	public $twitter;
	public $facebook;

	function __construct()
	{
		
		# Twitter #
		
		//require_once(__SITE_PATH.'/Pie/Pork/twittermodel/config.php');
		require_once(__SITE_PATH.'/Pie/Pork/twittermodel/twitteroauth/twitteroauth.php');
		
		# End Twitter #
		
		Jelly::__construct();
		
	}

	# End Header #
        
        public function Index($vars)
        {

#                if ($vars['q']=='pl9903HHGwwi21230pdsaslMl4323123ksas')
#                {
#                 	$_SESSION['userid'] = 31386162; /* RobDWaller */
#					$_SESSION['primaryid'] = 31386162;
#					$_SESSION['userid'] = 198192466; /* Status People */
#					$_SESSION['primaryid'] = 198192466;
#					$_SESSION['userid'] = 39068542;  
#					$_SESSION['primaryid'] = 39068542;
#					$_SESSION['userid'] = 1919216960; /* Fakers App */
#					$_SESSION['primaryid'] = 1919216960;
						
                    if (isset($_SESSION['message']))
                    {
                        $data['message'] = $_SESSION['message'];
                    }
                    elseif ($vars[0]==1) 
                    {
                        $data['message'] = Build::PageMessage('alert',array('Please connect to you Twitter account to make use of this service.'));
                    }

                    $data['homelink'] = $this->routechutney->HREF('/Fakers',$this->mod_rewrite);	
                    $data['title'] = 'Home : Fakers App from StatusPeople.com';

                    $spamrecords = $this->dbbind->GetFakersWall(3);

                    //$this->errorschutney->DebugArray($spamrecords);
					$data['menu'] = $this->_BuildMenu();
                    $data['spamrecords'] = $this->_BuildSpamRecords($spamrecords);    
					$data['logout'] = 2;
					
					//Sessions::UnsetSessions(array('message'));

					session_destroy();					
					$data['topScores'] = APIRequests::GetTopScores(6);

                    $this->glaze->view('Spam/index.php',$data);
#                }
#                else
#                {
#					$data["logout"] = 2;
#					$data['menu'] = $this->_BuildMenu();
#                    $data['logout'] = 2;
#					session_destroy();
#                    $this->glaze->view('Spam/maintenance.php',$data);
#                }

        }
        
		/*public function IcoTest()
		{
			$data['text'] = '<p class="ico">\|zxcvbnm,./<>?asdfghjkl;\'#:@~qwertyuiop[]{}`1234567890-=!"£$%^&*()_+</p>';
			$data['text'] .= '<p class="ico2">\|zxcvbnm,./<>?asdfghjkl;\'#:@~qwertyuiop[]{}`1234567890-=!"£$%^&*()_+</p>';
			$data['text'] .= '<p class="ico3">\|zxcvbnm,./<>?asdfghjkl;\'#:@~qwertyuiop[]{}`1234567890-=!"£$%^&*()_+</p>';
			
			$this->glaze->view('Spam/test.php',$data);
		}*/
		
		public function SessionTest()
		{
			$this->errorschutney->DebugArray($_SESSION);
		}
	
        public function Scores($vars)
        {
            	Generic::_IsLogin();
               
                $validity = $this->_CheckValidity($_SESSION['userid']);
				
			//$this->errorschutney->PrintArray($validity);
			
			//$this->errorschutney->PrintArray($_SESSION);
			
                if (!$validity[0])
                {
                    
                    if (!empty($validity[1]))
                    {
                        $data['message'] = $validity[1];
                    }
                    
                    if ($vars[0]==1)
                    {
                        $data['scores'] = $this->_BuildSpamScores($vars);
                    }

                    if (isset($_SESSION['message']))
                    {
                        $data['message'] = $_SESSION['message'];
                    }
					
					$details = $this->dbbind->GetTwitterDetails($_SESSION["userid"]);
					$verify = $this->twitterbind->Verify($details[2],$details[3]);
				
					#$this->errorschutney->DebugArray($verify);
                
					if ($verify['code']!=200)
					{
						$data['message'] = Build::PageMessage('failure',array('Your Twitter credentials have expired, please <a href="/Fakers/Reset">reset them now</a>.'));
					}
					
                    $data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
                    $data['title'] = 'Fakers Dasboard : Fakers App from StatusPeople.com';
					$data['twitterid'] = Validation::ObscureNumber($_SESSION['userid'],SALT_ONE);

                    $fields = array('email'=>array('Email','Text','',$_SESSION['email']),
                                'title'=>array('Title','Title','',$_SESSION['title']),
                                'firstname'=>array('First Name','Text','',$_SESSION['firstname']),
                                'lastname'=>array('Last Name','Text','',$_SESSION['lastname']),
                                'submit'=>array('Proceed','Submit')); 

                    $data['form'] = Forms::FormBuilder('detailsform',$this->routechutney->BuildUrl('/Payments/ProcessDetails',$this->mod_rewrite),$fields);

                    $url = $this->routechutney->HREF('/API/GetTwitterBio?rf=json&twid='.urlencode($data["twitterid"]),$this->mod_rewrite);
					
					if (isset($vars[5]))
                    {
                        $data['twitterhandle'] = $vars[5];
                    }
                    else 
                    {

                        $bio = CurlRequests::GetJSON($url);

						//$this->errorschutney->DebugArray($bio);
						
                        $data['twitterhandle'] = $bio->data->screenname;

                    }
					
					$searches = $this->dbbind->GetSearches($_SESSION['userid']);
					//$this->errorschutney->PrintArray($_SESSION);
					//$this->errorschutney->PrintArray($searches);
					setcookie('searches',$searches[0],time()+3600000);
					$data['searches'] = $searches[0];
					//$this->errorschutney->DebugArray($_COOKIE);
					$data['logout'] = 1;
					$data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);
					$data['menu'] = '&nbsp;';

                    Sessions::UnsetSessions(array('message'));
					
					//$this->errorschutney->DebugArray($data);
					
                    $this->glaze->view('Spam/scores.php',$data);
                }
                else 
                {
                    header('Location:'.$this->routechutney->BuildUrl('/Fakers/Dashboard',$this->mod_rewrite));
                    die();
                }
        }
        
        public function Dashboard($vars)
        {
            
            Generic::_IsLogin();
            
            $validity = $this->_CheckValidity($_SESSION['userid']);
			
			//$this->errorschutney->PrintArray($_SESSION);

            if ($validity[0])
            {

                $userid = Validation::ObscureNumber($_SESSION['userid'],SALT_ONE);

                $details = $this->dbbind->GetTwitterDetails($_SESSION["userid"]);
                
				//$this->errorschutney->PrintArray($details);
				
                $verify = $this->twitterbind->Verify($details[2],$details[3]);
				
				//$this->errorschutney->DebugArray($verify);
                
				if ($verify['code']!=200)
				{
					$data['message'] = Build::PageMessage('failure',array('Your Twitter credentials have expired, please <a href="/Fakers/Reset">reset them now</a>.'));
				}
				
                $url = $this->routechutney->HREF('/API/GetTwitterBio?rf=json&twid='.urlencode($userid),$this->mod_rewrite);

				//$this->errorschutney->PrintArray($url);
				
				$bio = CurlRequests::GetJSON($url);
                
				//$this->errorschutney->DebugArray($bio);

                $data['twitterhandle'] = $bio->data->screenname;

                $count = $this->dbbind->CheckForFakerCheck($_SESSION['userid'],$_SESSION['userid']);
                
				//$this->errorschutney->DebugArray($count);
				
                $data['firsttime'] = 0;
                
                if (!$count)
                {
                    $this->dbbind->AddFakerCheck($_SESSION['userid'],$_SESSION['userid'],$bio->data->screenname,$bio->data->image,1,time(),time());

                    $spam = $this->dbbind->GetSpamDetails($_SESSION['userid']);
                    
                    $this->dbbind->AddFakerCheckScore($_SESSION['userid'],$bio->data->screenname,$spam[2],$spam[3],$spam[4],$spam[5],time());
                
                    $data['firsttime'] = 1;
                }

//                $userid = 31386162;

				//$competitors = $this->dbbind->GetCompetitors($userid);
                $fakes = $this->dbbind->GetFakes($_SESSION['userid'],1,5);
				$blocked = $this->dbbind->GetFakes($_SESSION['userid'],0,5);
//            $this->errorschutney->PrintArray($competitors);
//            $this->errorschutney->DebugArray($fakes);

				//$data['competitors'] = $this->_BuildCompetitors($competitors);
				$blockcount = $this->dbbind->CountBlocked($_SESSION['userid']);
				
				$data['blockcount'] = number_format($blockcount);
				
                $data['fakes'] = $this->_BuildFakes($fakes,1);
				$data['blocked'] = $this->_BuildFakes($blocked,2);
				$data['homelink'] = $this->routechutney->HREF('/Fakers/Dashboard',$this->mod_rewrite);	

                $data['twitterid'] = $userid;
				$data['type'] = $_SESSION['type'];
				//$data['type'] = 2;
				$data['autoon'] = $this->dbbind->CountAutoRemoveRecords($_SESSION['userid']);
				
				//$this->errorschutney->PrintArray($_SESSION);
				//$this->errorschutney->DebugArray($data);
				
				setcookie('searches',1000,time()+3600000,'/');
				
				$data['accountform'] = $this->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
				
				$data['title'] = 'Fakers Dashboard : Fakers App from StatusPeople.com';
				
                $this->glaze->view('Spam/advanced.php',$data); 
            }
            else
            {
                header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));
                die();
            }
            
        }
        
		public function Followers()
		{
			Generic::_IsLogin();
            
            $validity = $this->_CheckValidity($_SESSION['userid']);

            if ($validity[0])
            {
				$userid = Validation::ObscureNumber($_SESSION['userid'],SALT_ONE);
				$userid2 = Validation::ObscureNumber($_SESSION['userid'],SALT_TWO);

                $details = $this->dbbind->GetTwitterDetails($_SESSION['userid']);
                
//                $verify = $this->twitterbind->Verify($details[2],$details[3]);
                
//                $this->errorschutney->DebugArray($verify);
                
                $url = $this->routechutney->HREF('/API/GetTwitterBio?rf=json&twid='.urlencode($userid),$this->mod_rewrite);

                $bio = CurlRequests::GetJSON($url);
                
//                $this->errorschutney->DebugArray($bio);

                $data['twitterhandle'] = $bio->data->screenname;

                $count = $this->dbbind->CheckForFakerCheck($_SESSION['userid'],$_SESSION['userid']);
                
                $data['firsttime'] = 0;
				
				if (!$count)
                {
                    $this->dbbind->AddFakerCheck($_SESSION['userid'],$_SESSION['userid'],$bio->data->screenname,$bio->data->image,1,time(),time());

                    $spam = $this->dbbind->GetSpamDetails($_SESSION['userid']);
                    
                    $this->dbbind->AddFakerCheckScore($_SESSION['userid'],$bio->data->screenname,$spam[2],$spam[3],$spam[4],$spam[5],time());
                
                    $data['firsttime'] = 1;
                }

//                $userid = 31386162;

                $competitors = $this->dbbind->GetCompetitors($_SESSION['userid']);
				//$fakes = $this->dbbind->GetFakes($userid,5);
				//echo $userid;
				//$this->errorschutney->DebugArray($competitors);
//            $this->errorschutney->DebugArray($fakes);

                $data['competitors'] = $this->_BuildCompetitors($competitors);
				//$data['fakes'] = $this->_BuildFakes($fakes);
				$data['homelink'] = $this->routechutney->HREF('/Fakers/Dashboard',$this->mod_rewrite);	

                $data['twitterid'] = $userid;
				$data['twitterid2'] = $userid2;
				$data['type'] = $_SESSION['type'];
				
				setcookie('searches',1000,time()+3600000,'/');
				
				$data['accountform'] = $this->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
				
				$data['title'] = 'Follower Analytics : Fakers App from StatusPeople.com';
				
                $this->glaze->view('Spam/followers.php',$data); 
			}
            else
            {
                header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));
                die();
            }
		}
	
		public function _ShowUserData($screen_name)
		{
			//die('Hello World!!');
			
			//$this->errorschutney->PrintArray($this->routechutney->HREF('/API/GetAPIScore?rf=json&ky=78cef6c5a5869e827ec2dd1396f1e66b413e6cdc656dcffdacf058897c5469f3&sc='.$screen_name,$this->mod_rewrite));
			
			$result = CurlRequests::GetJSON($this->routechutney->HREF('/API/GetAPIScore?rf=json&ky=78cef6c5a5869e827ec2dd1396f1e66b413e6cdc656dcffdacf058897c5469f3&sc='.$screen_name,$this->mod_rewrite));
		
			//$this->errorschutney->DebugArray($result);
			if ($result->code == 201)
			{
				//$this->errorschutney->DebugArray($_SESSION);
			
				$data['title'] = '@'.$result->data->screen_name.' | Faker Page';
			
				$data['screen_name'] = $result->data->screen_name;
				$data['fake'] = $result->data->fake;
				$data['inactive'] = $result->data->inactive;
				$data['good'] = $result->data->good;
				$data['followers'] = number_format($result->data->followers);
				$data['date'] = date('M jS, Y',$result->data->timestamp);
				$data['avatar'] = str_replace('http:','https:',$result->data->avatar);
				
				$data['logout'] = 2;
				
				if (isset($_SESSION['type'])&&($_SESSION['type']==0||$_SESSION['type']==1))
				{
					$data['logout'] = 1;
				}
				else if ($_SESSION['type']>=2)
				{
					$data['logout'] = 0;
					$data['accountform'] = self::_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
				}
				
				$data['menu'] = self::_BuildMenu();
				
				$data['topScores'] = APIRequests::GetTopScores(6);
				
				//$this->errorschutney->DebugArray($data['topScores']);
				
				$this->glaze->view('Spam/showuser.php',$data);
			}
			else
			{
				$data['title'] = 'Status People &mdash; Page Not Found.';
				$data['homelink'] = $this->routechutney->HREF('/User/Signup',$this->mod_rewrite);
				$data['message'] = Build::PageMessage('alert',array('Very sorry, but the page you were looking for could not be located.'));
				$data['menu'] = self::_BuildMenu();
				$data['logout'] = 2;
				$this->glaze->view('error.php',$data);
			}
		}
	
		public function Celebs()
		{
			$data['title'] = 'Celebrity Accounts: Fakers App From StatusPeople.com';
			
			$data['logout'] = 2;
			
			if (isset($_SESSION['type'])&&($_SESSION['type']==0||$_SESSION['type']==1))
			{
				$data['logout'] = 1;
			}
			else if ($_SESSION['type']>=2)
			{
				$data['logout'] = 0;
				$data['accountform'] = self::_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
			$data['menu'] = self::_BuildMenu();
			
			$data['topScores'] = APIRequests::GetTopScores(300);
			
			//$this->errorschutney->DebugArray($data['topScores']);
			
			$this->glaze->view('Spam/topaccounts.php',$data);
		}
	
		public function Settings()
		{
			//$this->errorschutney->DebugArray($_SESSION);
			
			Generic::_IsLogin();
			
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if (!$validity[0])
            {
				$data['logout'] = 1;
				$data['menu'] = '&nbsp;';
			}	
				
			if (isset($_SESSION['message']))
			{
				$data['message'] = $_SESSION['message'];
			}
			
			Sessions::UnsetSessions(array('message'));
			
			$data['homelink'] = $this->routechutney->HREF('/Fakers',$this->mod_rewrite);
			$data['title'] = 'Settings : Fakers App from StatusPeople.com';
			
			$details = PaymentRequests::GetUserDetails($_SESSION['userid']);
			
			//$this->errorschutney->DebugArray($details);
			
			$fields = array('email'=>array('Email','Text','',$details[2]),
							'title'=>array('Title','Title','',$details[3]),
							'firstname'=>array('First Name','Text','',$details[4]),
							'lastname'=>array('Last Name','Text','',$details[5]),
							'submit'=>array('Update','Submit')); 
			
			$data['form'] = Forms::FormBuilder('yourdetailsform',$this->routechutney->BuildUrl('/Payments/UpdateDetails',$this->mod_rewrite),$fields);
			
			$competitors = $this->dbbind->GetCompetitors($_SESSION['userid']);
			
			//$this->errorschutney->DebugArray($competitors);
			
			$data['accounts'] = $this->_BuildSubAccounts($competitors);
			
			$check = APIRequests::CheckForUsersKey($_SESSION['userid']);
			
			//$this->errorschutney->DebugArray($check);
			
			if ($check>0)
			{
				$key = APIRequests::GetUsersKey($_SESSION['userid']);
				
				$data['apikey'] = $key[1];
			}
			else
			{
				$apikey = $hash = Validation::HashString(time().$_SESSION['userid'].rand(0,9999));
				
				APIRequests::AddKey($_SESSION['userid'],$hash,time());
				
				$data['apikey'] = $apikey;
			}
			
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
			$data['type'] = $_SESSION['type'];
			$data['twitterid'] = Validation::ObscureNumber($_SESSION['userid'],SALT_ONE);
			$data['nosettings'] = $_SESSION['nosettings'];
			
			$this->glaze->view('Spam/settings.php',$data);	
		}
	
		public function ResetAPI()
		{
			Generic::_IsLogin();
			
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if ($validity[0])
            {
				$hash = Validation::HashString(time().$_SESSION['userid'].rand(0,9999));
				
				$update = APIRequests::ResetKey($_SESSION['userid'],$hash);
				
				if ($update > 0)
				{
					$_SESSION['message'] = Build::PageMessage('success',array('API Key reset successfully.'));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
				}
				else
				{
					$_SESSION['message'] = Build::PageMessage('failure',array('Failed to Reset API key, please contact info@statuspeople.com.'));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
                	die();	
				}
			}
            else
            {
                header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));
                die();
            }
		}
	
		public function Sites()
		{
			$data['homelink'] = $this->routechutney->HREF('/Fakers',$this->mod_rewrite);
			$data['title'] = 'The Faker Sites';
			
			//$sites = $this->dbbind->GetSites();
			
			//$data['sites'] = $this->_BuildSites($sites);
			
			$this->glaze->view('Spam/sites.php',$data);
		}
		
		public function SitesAdmin($vars)
		{
			if ($vars['p']=='fghe983k1')
			{
				$data['homelink'] = $this->routechutney->HREF('/Fakers/SitesAdmin',$this->mod_rewrite);
				$data['title'] = 'The Faker Sites Admin';
				
				$sites = $this->dbbind->GetSites();
				
				$data['sites'] = $this->_BuildSitesAdmin($sites);
				
				$this->glaze->view('Spam/sitesadmin.php',$data);
			}
		}
	
		public function DeepDiveAdminScores($vars)
		{
			if ($vars['p']='yhd763jei')
			{
				$data['homelink'] = $this->routechutney->HREF('/Fakers/DeepDiveAdminScores',$this->mod_rewrite);
				$data['title'] = 'Deep Dive Admin Scores';
				
				$dives = DeepdiveRequests::GetAllDiveScores();
				
				//$this->errorschutney->DebugArray($dives);
				
				$data['dives'] = $this->_BuildDiveScores($dives);
				
				$this->glaze->view('Spam/diveadmin.php',$data);
			}
		}
	
        public function Reset()
        {
            Generic::_IsLogin();
            
            if (isset($_SESSION['message']))
            {
                $data['message'] = $_SESSION['message'];
            }
            
			Sessions::UnsetSessions(array('message'));
			
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
			$data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
			$data['title'] = 'Twitter Reset : Fakers App from StatusPeople.com';
			
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if (!$validity[0])
            {
				$data['logout'] = 1;
				$data['menu'] = '&nbsp;';
				
				if ($_SESSION['userid']<1)
				{
					$data['logout'] = 2;
					$data['menu'] = $this->_BuildMenu();
				}
			}
			
            $this->glaze->view('Spam/reset.php',$data);
        }

        public function ResetConnectionDetails()
        {
            Generic::_IsLogin();
            
            $userid = $_SESSION['userid'];
            
            $reset = $this->dbbind->ResetTwitterDetails($userid);
            
            if ($reset)
            {
                $_SESSION['message'] = Build::PageMessage('success',array('Connection Details Reset Successfully. Please now reconnect to the Fakers App.'));
                
                header('Location:'.$this->routechutney->BuildUrl('/',$this->dbbind));
            }
            else
            {
                $_SESSION['message'] = Build::PageMessage('failure',array('Failed to reset connection details. Please contact info@statuspeople.com'));
                
                header('Location:'.$this->routechutney->BuildUrl('/Fakers/Reset',$this->dbbind));
            }
        }
        
		public function Help()
		{
			$data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
            $data['title'] = 'Help : Fakers App from StatusPeople.com';
			
			if (!empty($_SESSION['userid']))
			{
				$data['twitterid'] = Validation::ObscureNumber($_SESSION['userid'],SALT_ONE);
			}
			
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if (!$validity[0])
            {
				$data['logout'] = 1;
				$data['menu'] = '&nbsp;';
				
				if ($_SESSION['userid']<1)
				{
					$data['logout'] = 2;
					$data['menu'] = $this->_BuildMenu();
				}
			}
			
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
			$this->glaze->view('Spam/help.php',$data);
		}
	
        public function FindOutMore()
        {
            $data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
            $data['title'] = 'Find Out More : Fakers App from StatusPeople.com';
			
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if (!empty($_SESSION['userid']))
			{
				$data['twitterid'] = Validation::ObscureNumber($_SESSION['userid'],SALT_ONE);
			}
			
			if (!$validity[0])
            {
				$data['logout'] = 1;
				$data['menu'] = '&nbsp;';
				
				if ($_SESSION['userid']<1)
				{
					$data['logout'] = 2;
					$data['menu'] = $this->_BuildMenu();
				}
			}
			
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
			$this->glaze->view('Spam/info.php',$data);
        }
        
        public function Terms()
        {
            $data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
            $data['title'] = 'Terms and Conditions : Fakers App from StatusPeople.com';
			
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if (!empty($_SESSION['userid']))
			{
				$data['twitterid'] = Validation::ObscureNumber($_SESSION['userid'],SALT_ONE);
			}
			
			if (!$validity[0])
            {
				$data['logout'] = 1;
				$data['menu'] = '&nbsp;';
				
				if ($_SESSION['userid']<1)
				{
					$data['logout'] = 2;
					$data['menu'] = $this->_BuildMenu();
				}
			}
			
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
			
			$this->glaze->view('Spam/terms.php',$data);
        }
        
        public function Wall()
        {
            $data['homelink'] = $this->routechutney->HREF('/',$this->mod_rewrite);	
            $data['title'] = 'Wall of Shame : Fakers App from StatusPeople.com';
            
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if (!empty($_SESSION['userid']))
			{
				$data['twitterid'] = Validation::ObscureNumber($_SESSION['userid'],SALT_ONE);
			}
			
			if (!$validity[0])
            {
				$data['logout'] = 1;
				$data['menu'] = '&nbsp;';
				
				if ($_SESSION['userid']<1)
				{
					$data['logout'] = 2;
					$data['menu'] = $this->_BuildMenu();
				}
			}
			
            $spamrecords = $this->dbbind->GetFakersWall(99);
            
            $data['spamrecords'] = $this->_BuildSpamRecords($spamrecords);  
			
			if ($_SESSION['type']>=1)
			{
				$data['accountform'] = $this->_BuildAccountsForm($_SESSION['primaryid'],$_SESSION['userid']);
			}
            
            $this->glaze->view('Spam/fakers.php',$data);
        }
        
        public function Goodies($vars)
        {
            if ($vars['q']=='78asoy8_op789')
            {
                $data['homelink'] = $this->routechutney->HREF('/',$this->mod_rewrite);	
                $data['title'] = 'Status People Fake Follower Check';

                $members = $this->dbbind->Get500kClub();

                $data['goodies'] = $this->_Build500kClub($members);  

                $this->glaze->view('Spam/goodies.php',$data);
            }
        }
	
		public function Unsubscribe($vars)
		{
			$data['email'] = $vars['e'];
			
			$valid = Validation::ValidateEmail($data['email']);
			
			//$this->errorschutney->DebugArray($valid);
			
			if (!$valid[0])
			{
				$data['message'] = Build::PageMessage('failure',array("This is not a valid email address. Please check the address and try again."));
			}
			else
			{
				$count = $this->dbbind->CheckMarketingEmail($data['email']);
				
				if ($count)
				{
					$unsubscribe = $this->dbbind->UnsubscribeEmail($data['email']);
					
					if ($unsubscribe>0)
					{
						$data['message'] = Build::PageMessage('success',array("Your email has been successfully unsubscribed. We're sorry to see you go."));
					}
					else
					{
						$data['message'] = Build::PageMessage('failure',array("There was an error! We failed to unsubscribe your email address, please contact info@statuspeople.com."));
					}
				}
				else
				{
					$data['message'] = Build::PageMessage('alert',array("This email address has either already been unsubscribed or does not exist within our systems."));
				}
			}
			
			$data['homelink'] = $this->routechutney->HREF('/',$this->mod_rewrite);	
            $data['title'] = 'Unsubscribe : Fakers App from StatusPeople.com';
			$data['logout'] = 1;
			
			$this->glaze->view('Spam/unsubscribe.php',$data);
		}
        
/*         public function Extend()
        {
            $oldtime = 1355180905;
            
            $newtime = strtotime(date('Y/m/d',$oldtime).' +1 Months');
            
            $this->errorschutney->PrintArray($oldtime);
            $this->errorschutney->PrintArray($newtime);
            $this->errorschutney->PrintArray(date('Y/m/d',$newtime));
        } */
	
        public function GetScores()
        {
            
            Generic::_IsLogin();
            
            $userid = $_SESSION['userid'];
            $search = $_POST['name'];

            $url = $this->routechutney->HREF('/API/GetSpamScores?rf=json&usr='.$userid.'&srch='.$search,$this->mod_rewrite);
            
            $scores = CurlRequests::GetJSON($url);
       
            //$this->errorschutney->DebugArray($scores);
            
            $result = 2;
            $followers = 0;
            $checks = 0;
            $potential = 0;
            $spam = 0;
            
            if ($scores->code==201)
            {
                $result = 1;
                $followers = $scores->data->followers;
                $checks = $scores->data->checks;
                $potential = $scores->data->potential;
                $spam = $scores->data->spam;
            }
            
            header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores/V/'.$result.'/'.$followers.'/'.$checks.'/'.$potential.'/'.$spam.'/'.$search,$this->mod_rewrite));
            
        }
	
		public function DisconnectAccount()
		{
			Generic::_IsLogin();
			
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if ($validity[0])
            {
				$parentid = Validation::UnobscureNumber($_POST['parentid'],SALT_ONE);
				$childid = Validation::UnobscureNumber($_POST['childid'],SALT_ONE);
				
				$exists = $this->dbbind->CheckForParent($parentid,$childid);
				
				if ($exists>0)
				{
					$delete = $this->dbbind->DeleteParentUserRelationship($parentid,$childid);
					
					if ($delete > 0)
					{
						$_SESSION['message'] = Build::PageMessage('success',array("Accounts disconnected successfully."));
						header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
						die();	
					}
					else
					{
						$_SESSION['message'] = Build::PageMessage('failure',array("Failed to disconnect accounts. Please try again or contact info@statuspeople.com."));
						header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
						die();
					}
				}
				else
				{
					$_SESSION['message'] = Build::PageMessage('failure',array("These accounts are already disconnected."));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
                	die();
				}
			}
            else
            {
                header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));
                die();
            }
		}
	
		public function SwitchAccount()
		{
			Generic::_IsLogin();
			
			$validity = $this->_CheckValidity($_SESSION['userid']);
			
			if ($validity[0])
            {
				
				$_SESSION['userid'] = Validation::UnobscureNumber($_POST['account'],SALT_ONE);
				$userid = Validation::UnobscureNumber($_POST['account'],SALT_ONE);
				
				$count = PaymentRequests::CountValidRecords($_SESSION['userid']);
				
				$_SESSION['nosettings'] = 1;
				//$this->errorschutney->PrintArray($count);
				
				if ($_SESSION['primaryid']==$userid)
				{
					$_SESSION['nosettings'] = 0;
				}
				
				if ($count)
				{
					$validdate = PaymentRequests::GetValidDate($userid);
					
					//$this->errorschutney->DebugArray($validdate);
					
					if ($validdate[0]>time())
					{
						$valid = true;
						$_SESSION['type'] = $validdate[1];
					}
				}
				else
				{
					$_SESSION['type'] = 2;
				}
				
				$user = $this->twitterbind->GetUserByID($token,$secret,$userid);
				
				//$this->errorschutney->DebugArray($user);
				
				if ($user['code'] == 200)
				{
					$bio = $user['user'];
					
					$countinfo = $this->dbbind->CountUserInfoRecords($userid); 
					
					if ($countinfo==0)
					{
						$this->dbbind->AddUserInfo($userid,$bio->screen_name,$bio->profile_image_url,time(),time());
					}
					else
					{
						$this->dbbind->UpdateUserInfo($userid,$bio->screen_name,$bio->profile_image_url);
					}
				}
				
				header('Location:'.$this->routechutney->BuildUrl('/Fakers/Dashboard',$this->mod_rewrite));
				die();
			}
			else
			{
				header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));
                die();
			}
		}
/* 	public function TwitterSuccess($vars)
	{
            
            if ($_SESSION['rsp'] == 400)
            {
                $_SESSION['Twitter'] = 1;
                $data['message'] = Build::PageMessage('failure',array("There was an error authenticating with Twitter. Please try again, if this problem persists contact info@statuspeople.com."));
                header('Location:'.$this->routechutney->BuildUrl('/Fakers',$this->mod_rewrite));
            }
            elseif ($_SESSION['rsp'] == 200)
            {

                $userid = Validation::UnobscureNumber($_SESSION['ui'],SALT_ONE);
                $token = $_SESSION['oat'];
                $secret = $_SESSION['oas'];
                $where = $_SESSION['var1'];
                
                $_SESSION['userid'] = Validation::UnobscureNumber($_SESSION['ui'],SALT_ONE);
                $_SESSION['token'] = $_SESSION['oat'];
                $_SESSION['secret'] = $_SESSION['oas'];

				//$this->errorschutney->PrintArray($_SESSION);
				//$this->errorschutney->PrintArray($userid);

				$ok = false;
				
				$valid = Validation::ValidateInteger($userid,'Twitter ID');
				
				if ($valid[0])
				{
					$exists = $this->dbbind->CountUsers($userid);
					
					//$this->errorschutney->PrintArray($exists);
					
					if ($exists > 0)
					{
						$ok = true;
						//die('1');
					}
					else 
					{
						//die('2');
						
						$bio = $this->twitterbind->GetUserByID($token,$secret,$userid);
						
						//$this->errorschutney->DebugArray($bio);
						
						$result = $this->dbbind->AddTwitterDetails($userid,$token,$secret,time());
						
						if ($result > 0)
						{
							$ok = true;
							//die(2);
						}
						
						$countinfo = $this->dbbind->CountUserInfoRecords($userid); 
						
						if ($countinfo==0)
						{
							$this->dbbind->AddUserInfo($userid,$bio->screen_name,$bio->profile_image_url,time(),time());
						}
							
					}
				}
                
                //$result = 1;
                
                Sessions::UnsetSessions(array('returnurl','var1','oauth_token_secret','ui','oat','oas'));
                
                if ($ok)
                {
					$ip = $_SERVER["REMOTE_ADDR"];
                    //$_SESSION['message'] = Build::PageMessage('success',array('Twitter successfully authenticated.'));
                    $this->dbbind->AddLogin($userid,$ip,time());
					Generic::_LastPage();
                    header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));   
                }
                else
                {
                    $_SESSION['message'] = Build::PageMessage('failure',array("There was an error with the Twitter authentication process. Please try again, if this problem persists contact info@statuspeople.com."));
                    header('Location:'.$this->routechutney->BuildUrl('/Fakers',$this->mod_rewrite));
                }
            }
			else
			{
				$_SESSION['Twitter'] = 1;
                $data['message'] = Build::PageMessage('failure',array("There was an error authenticating with Twitter. Please try again, if this problem persists contact info@statuspeople.com."));
                header('Location:'.$this->routechutney->BuildUrl('/Fakers',$this->mod_rewrite));
			}
	} */
	
	public function AuthenticateTwitter()
	{
		
            $_SESSION['returnurl'] = $_POST['ru'];
            $_SESSION['var1'] = $_POST['var1'];
		
			$urlAppend = "";
		
			if (isset($_POST['parentid']))
			{
				$_SESSION['parentid'] = Validation::UnobscureNumber($_POST['parentid'],SALT_ONE);
				$_SESSION['childid'] = Validation::UnobscureNumber($_POST['childid'],SALT_ONE);
				$urlAppend = "&force_login=true";
			}
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

            $request_token = $this->twitter->getRequestToken(OAUTH_CALLBACK);

            /* Save temporary credentials to session. */

            $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			
		//$this->errorschutney->DebugArray($_SESSION);
		
            /* If last connection failed don't display authorization link. */
            switch ($this->twitter->http_code) {
                case 200:
                    /* Build authorize URL and redirect user to Twitter. */
                    $url = $this->twitter->getAuthorizeURL($token,FALSE);
                    header('Location: ' . $url . $urlAppend); 
                    break;
                default:
                    /* Show notification if something went wrong. */
//                                    $_SESSION['message'] = Build::ErrorMessages(array('Could not connect to Twitter. Refresh the page or try again later.'));
//                                    $redirect = $this->routechutney->BuildUrl('/User/Account',$this->mod_rewrite);
//                                    header('Location:'.$redirect);
                      $this->ClearTwitterSessions();
            }
                		
	}
	
	public function TwitterCallback($vars)
	{
		
		if (isset($vars['oauth_token']) && $_SESSION['oauth_token'] !== $vars['oauth_token']) {
			$_SESSION['oauth_status'] = 'oldtoken';
			$redirect = $this->routechutney->BuildUrl('/Fakers/ClearTwitterSessions');
			header('Location:'.$redirect);
		}

		/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
		$this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

		/* Request access tokens from twitter */
		$access_token = $this->twitter->getAccessToken($vars['oauth_verifier']);

		/* Save the access tokens. Normally these would be saved in a database for future use. */
		//$_SESSION['access_token'] = $access_token;

		/* Remove no longer needed request tokens */
		
		Sessions::UnsetSessions(array('oauth_token','oauth_token_secrect'));

		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $this->twitter->http_code) {
			/* The user has been verified and the access tokens can be saved for future use */
/* 			if ($access_token['user_id']==2147483647)
			{
				$headers['from'] = 'StatusPeople <info@statuspeople.com>';
				$headers['reply'] = 'info@statuspeople.com';
				$headers['return'] = 'info@statuspeople.com';
				
				$message = print_r(array($this->twitter,$access_token),true);
			
				$send = Email::SendEmail('rdwaller1984@googlemail.com','Sign Up Warning for 2147483647',$message,$headers,0);
			} */
			
/* 			$_SESSION['status'] = 'verified';
			$_SESSION['ui'] = Validation::ObscureNumber($access_token['user_id'],SALT_ONE);
			$_SESSION['oat'] = $access_token['oauth_token'];
			$_SESSION['oas'] = $access_token['oauth_token_secret'];
			$_SESSION['rsp'] = 200; */
			
			$ok = false;
			
			$userid = $access_token['user_id'];
			$token = $access_token['oauth_token'];
			$secret = $access_token['oauth_token_secret'];
			
			$_SESSION['userid'] = $access_token['user_id'];
			$_SESSION['primaryid'] = $access_token['user_id'];
            $_SESSION['token'] = $access_token['oauth_token'];
            $_SESSION['secret'] = $access_token['oauth_token_secret'];
			
			$exists = $this->dbbind->CountUsers($userid);
					
					//$this->errorschutney->PrintArray($exists);
					
			if ($exists > 0)
			{
				$ok = true;
				//die('1');
				
				$user = $this->twitterbind->GetUserByID($token,$secret,$userid);
				
				//$this->errorschutney->DebugArray($bio);
				
				if ($user['code'] == 200)
				{
					$bio = $user['user'];
					
					$countinfo = $this->dbbind->CountUserInfoRecords($userid); 
					
					if ($countinfo==0)
					{
						$this->dbbind->AddUserInfo($userid,$bio->screen_name,$bio->profile_image_url,time(),time());
					}
					else
					{
						$this->dbbind->UpdateUserInfo($userid,$bio->screen_name,$bio->profile_image_url);
					}
				}
			}
			else 
			{
				//die('2');
				
				$user = $this->twitterbind->GetUserByID($token,$secret,$userid);
				
				//$this->errorschutney->DebugArray($bio);
				
				if ($user['code'] == 200)
				{
					$bio = $user['user'];
					
					$result = $this->dbbind->AddTwitterDetails($userid,$token,$secret,time());
					
					if ($result > 0)
					{
						$ok = true;
						//die(2);
						
						$countinfo = $this->dbbind->CountUserInfoRecords($userid); 
					
						if ($countinfo==0)
						{
							$this->dbbind->AddUserInfo($userid,$bio->screen_name,$bio->profile_image_url,time(),time());
						}
					}
				}
			}
			
/* 			$redirect = $_SESSION['returnurl'];
			header('Location:'.$redirect);
			die(); */
			
			if (isset($_SESSION['parentid'])&&isset($_SESSION['childid']))
			{
				$_SESSION['userid'] = $_SESSION['parentid'];
				$_SESSION['primaryid'] = $_SESSION['parentid'];
            	
				//$this->errorschutney->DebugArray($_SESSION);
				
				if ($userid == $_SESSION['childid'])
				{
					$checkforparent = $this->dbbind->CheckForParent($_SESSION['parentid'],$_SESSION['childid']);
					
					if ($checkforparent==0)
					{
						$addparent = $this->dbbind->AddParent($_SESSION['parentid'],$_SESSION['childid'],time());
						
						Sessions::UnsetSessions(array('returnurl','var1','oauth_token_secret','ui','oat','oas','parentid','childid'));
						
						//$this->errorschutney->DebugArray($addparent);
						
						if ($addparent>0)
						{					
							if ($ok)
							{
								header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));   
							}
							else
							{
								$_SESSION['message'] = Build::PageMessage('failure',array("Failed to connect to sub-account. Please try again, if this problem persists contact info@statuspeople.com."));
								header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
							}
						}
						else
						{
							$_SESSION['message'] = Build::PageMessage('failure',array("Process failed. Please contact info@statuspeople.com."));
							header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
						}
					}
					else
					{
						$_SESSION['message'] = Build::PageMessage('alert',array("These accounts are already connected."));
						header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
					}
				}
				else
				{
					Sessions::UnsetSessions(array('returnurl','var1','oauth_token_secret','ui','oat','oas','parentid','childid'));
					
					$_SESSION['message'] = Build::PageMessage('alert',array("You connected to the wrong account. Please try again..."));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Settings',$this->mod_rewrite));
				}
			}
			else
			{
				Sessions::UnsetSessions(array('returnurl','var1','oauth_token_secret','ui','oat','oas','parentid','childid'));
            
				if ($ok)
				{
					$ip = $_SERVER["REMOTE_ADDR"];
					//$_SESSION['message'] = Build::PageMessage('success',array('Twitter successfully authenticated.'));
					$this->dbbind->AddLogin($userid,$ip,time());
					//Generic::_LastPage();
					header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));   
				}
				else
				{
					$_SESSION['message'] = Build::PageMessage('failure',array("There was an error with the Twitter authentication process. Please try again, if this problem persists contact info@statuspeople.com."));
					header('Location:'.$this->routechutney->BuildUrl('/Fakers',$this->mod_rewrite));
				}
			}
		} 
		else 
		{
			/* Save HTTP status for error dialog on connnect page.*/
			$_SESSION['message'] = Build::PageMessage('failure',array('Failed to connect to Twitter please try again.'));
			$redirect = $this->routechutney->BuildUrl('/Fakers/ClearTwitterSessions',$this->mod_rewrite);
			header('Location:'.$redirect);
		}
		
	}
	
	public function ClearTwitterSessions()
	{
		
                Sessions::UnsetSessions(array('status','oauth_token','oauth_token_secret','access_token','oauth_status'));

                header('Location:'.$_SESSION['returnurl'].'?rsp=400');
		
	}
		
		protected function _BuildDiveScores($scores)
		{
			if (!empty($scores))
			{
				$output = '<table>';
				$output .= '<tr><th>Screen Name</th><th>Fake</th><th>Inactive</th><th>Good</th><th>Checks</th><th>Followers</th></tr>';
				
				foreach ($scores as $s)
				{
					$fake = round(($s['spam']/$s['checks'])*100);
					$inactive = round(($s['potential']/$s['checks'])*100);
					$good = 100-($fake+$inactive);
					
					$output .= '<tr><td>'.$s['screen_name'].'</td><td class="red"><strong>'.$fake.'%</strong></td><td class="orange"><strong>'.$inactive.'%</strong></td><td class="green"><strong>'.$good.'%</strong></td><td>'.number_format($s['checks']).'</td><td>'.number_format($s['followers']).'</td></tr>';
				}
				
				$output .= '</table>';
			}
			
			return $output;
		}
	
		protected function _BuildSubAccounts($scores)
		{
			//$this->errorschutney->PrintArray($scores);
			
			if (!empty($scores))
			{
				$output = '<table>';
				$output .= '<tr><th>&nbsp;</th><th>Screen Name</th><th>&nbsp;</th></tr>';
				
				foreach ($scores as $s)
				{
					//$this->errorschutney->PrintArray($_SESSION['userid']);
					//$this->errorschutney->DebugArray($s);
					
					if ($s['twitterid']!=$_SESSION['userid'])
					{	
						$connected = $this->dbbind->CheckForParent($_SESSION['userid'],$s['twitterid']);
						
						$url = '/Fakers/AuthenticateTwitter';
						$button = 'Connect';
						
						if ($connected>0)
						{
							$url = '/Fakers/DisconnectAccount';
							$button = 'Disconnect';
						}
						
						$output .= '<tr><td><img class="connect" src="'.str_replace('http:','https:',$s['avatar']).'" height="48px" width="48px" /></td><td><p class="sf2 sp2 blue">'.$s['screen_name'].'</p></td><td><form method="post" action="'.$this->routechutney->HREF($url,$this->mod_rewrite).'"><input type="hidden" name="parentid" value="'.Validation::ObscureNumber($s['userid'],SALT_ONE).'" /><input type="hidden" name="childid" value="'.Validation::ObscureNumber($s['twitterid'],SALT_ONE).'" /><fieldset><input type="submit" value="'.$button.'"/></fieldset></form></td></tr>';
					}
				}
				
				$output .= '</table>';
			}
			else
			{
				$output = '<p class="sf2 sp2 blue">Please Add Some Accounts to Your Friends List.</p>';
			}
			
			return $output;
		}
	
 		public function _BuildAccountsForm($parentid,$userid)
		{
			$children = $this->dbbind->YourChildren($parentid);
			
			//$this->errorschutney->DebugArray($children);
			
			if (!empty($children)&&($_SESSION['type']>=3)||($parentid!=$userid))
			{
				$parent = $this->dbbind->GetUserInfo($parentid);
				
				$imagestring = '<img src="'.str_replace('http:','https:',$parent[3]).'" height="30" width="30" />';
				
				$form = '<form id="changeaccountform" action="/Fakers/SwitchAccount" method="post">';
				$form .= '<select name="account" id="account" class="accountselection icon" data-tip="Change Account">';
				$form .= '<option value="'.Validation::ObscureNumber($parentid,SALT_ONE).'"'.($parentid==$userid?' SELECTED':'').'>'.$parent[2].'</option>';
					
				foreach ($children as $ch)
				{
					$form .= '<option value="'.Validation::ObscureNumber($ch['twitterid'],SALT_ONE).'"'.($ch['twitterid']==$userid?' SELECTED':'').'>'.$ch['screen_name'].'</option>';
					
					if ($ch['twitterid']==$userid)
					{
						$imagestring = '<img src="'.str_replace('http:','https:',$ch['avatar']).'" height="30" width="30" />';
					}
				}
					
				$form .= '</select>';	
				$form .= '</form>';
				
				//$this->errorschutney->DebugArray($parent);
				
				$output = $imagestring.$form;
			}
			else
			{
				$parent = $this->dbbind->GetUserInfo($parentid);
				$imagestring = '<img src="'.str_replace('http:','https:',$parent[3]).'" height="30" width="30" /><div id="accountname">'.$parent[2].'</div>';
				$output = $imagestring;
			}
			
			return $output;
		}
	
		protected function _BuildSitesAdmin($sites)
		{
			if (!empty($sites))
			{
				$output = '<ul id="fakersuggestions">';
				
				foreach ($sites as $s)
				{
					$output .= '<li>';
					$output .= '<p><a href="'.$s['url'].'" target="_blank">'.$s['title'].'</a></p>';
					$output .= '<fieldset><input type="hidden" class="siteid" value="'.$s['id'].'" /></fieldset>';
					$output .= '<fieldset><input type="text" class="sitetext" value="'.$s['title'].'" /></fieldset>';
					$output .= '<fieldset><input type="text" class="siteurl" value="'.$s['url'].'"/></fieldset>';
					$output .= '<fieldset><input type="text" class="siteimage" value=""/></fieldset>';
					$output .= '<fieldset><input type="text" class="sitedescription" value=""/></fieldset>';
					$output .= '<p><a href="" class="siteadd">Add</a> <a href="" class="sitedelete">Delete</a></p>';
					$output .= '</li>';
				}
				
				$output .= '</ul>';
				
				return $output;
			}
		}
	
        protected function _BuildSpamScores($scores)
        {
            
            $spam = round(($scores[4]/$scores[2])*100,0);
            $potential = round(($scores[3]/$scores[2])*100,0);
            
            $output = '<div id="scoresholder">';
            $output .= '<div class="three center">';
            $output .= '<h1 class="red">Fake</h1>';
            $output .= '<h2 class="red">'.$spam.'%</h2>';
            $output .= '</div>';
            $output .= '<div class="three center">';
            $output .= '<h1>Inactive</h1>';
            $output .= '<h2>'.$potential.'%</h2>';
            $output .= '</div>';
            $output .= '<div class="three center">';
            $output .= '<h1 class="green">Good</h1>';
            $output .= '<h2 class="green">'.(100-($spam+$potential)).'%</h2>';
            $output .= '</div>';
            $output .= '</div>';
         
            return $output;
        }
        
        protected function _BuildSpamRecords($records)
        {
            
            $i = 0;
            $k = 0;
            
            foreach ($records as $r)
            {
                $spam = round(($r['spam']/$r['checks'])*100,0);
                $potential = round(($r['potential']/$r['checks'])*100,0);
                
                $output .= '<div class="three">';
                $output .= '<a href="/'.$r['screen_name'].'" class="pageLink"><img src="'.str_replace('http:','https:',$r['avatar']).'" alt="'.$r['screen_name'].'" /> @'.$r['screen_name'].'<br/>';
				$output .= '<small>Followers: '.number_format($r['followers']).'<span>'.$spam.'% Fake</span></small>';
                $output .= '</div>';
                
                $i++;
            }
            
			//$output .= '</div>';
            
            return $output;
        }
        
        protected function _Build500kClub($records)
        {
            
            $i = 0;
            $k = 0;
            
            foreach ($records as $r)
            {
                $spam = round(($r['spam']/$r['checks'])*100,0);
                $potential = round(($r['potential']/$r['checks'])*100,0);
                
                if ($i==$k)
                {
                    $output .= '<div class="row">';
                    $k+=3;
                }
                
                $output .= '<div class="three'.($i<($k-1)?' a':'').'">';
                $output .= '<img src="'.$r['avatar'].'" height="28px" width="28px" />';
                $output .= '<span class="spamscore"><a href="https://twitter.com/'.$r['screen_name'].'" target="_blank">'.$r['screen_name'].':</a> <span class="green">'.$spam.'% Fake</span></span>';
                $output .= '</div>';
                
                if ($i==($k-1))
                {
                    $output .= '</div>';
                }
                
                $i++;
            }
            
            $output .= '</div>';
            
            return $output;
        }
        
        protected function _BuildFakes($fakes,$type)
        {
            if (!empty($fakes))
            {
                
                $output = '<ul class="fakeslist">';
                
                foreach ($fakes as $f)
                {
					$output .= '<li><input type="hidden" value="'.$f['screen_name'].'" class="sc" /><input type="hidden" value="'.$f['twitterid'].'" class="ti"/><img src="'.str_replace('http:','https:',$f['avatar']).'" width="48px" height="48px" /> <span>'.$f['screen_name'].'</span><small><a href="#details" class="details">Details</a> | '.($type==1?'<a href="#block" class="block">Block</a> | <a href="#spam" class="notspam">Not Spam</a>':'<a href="#unblock" class="unblock">Unblock</a>').'</small></li>';
                }
                
                $output .= '</ul>';
            }
            else 
            {
				if ($type==1)
				{
					$output = '<div id="checkform"><p>No Fake Followers found at this time.</p><p><fieldset><input type="button" id="checkfakes" value="Check For New Fakes"/></fieldset></p></div>';
				}
			}
            
            return $output;
        }
        
        protected function _BuildCompetitors($competitors)
        {
			//$this->errorschutney->DebugArray($competitors);
			
            if (!empty($competitors))
            {
                
                $output = '<table class="competitorlist">';
                
                foreach ($competitors as $c)
                {
                    $fake = round(($c['spam']/$c['checks'])*100);
                    $inactive = round(($c['potential']/$c['checks'])*100);
                    $good = (100-($fake+$inactive));
                    
                    $output .= '<tr>';
					$output .= '<td><a href="https://twitter.com/'.$c['screen_name'].'" target="_blank" date-Up="'.$c['updated'].'"><img src="'.str_replace('http:','https:',$c['avatar']).'" width="36px" height="36px" /></a></td>';
                    $output .= '<td><span class="blue details pointer" data-sc="'.$c['screen_name'].'">'.$c['screen_name'].'</span></td>';
                    $output .= '<td><span class="red">Fake: '.$fake.'%</span></td>';
                    $output .= '<td><span class="orange">Inactive: '.$inactive.'%</span></td>';
                    $output .= '<td><span class="green">Good: '.$good.'%</span></td>';
                    $output .= '<td><input type="hidden" value="'.Validation::ObscureNumber($c['twitterid'],SALT_TWO).'" class="ti"/><input type="hidden" value="'.$c['screen_name'].'" class="sc"/><span class="chart icon" data-tip="View on chart"><img src="/Pie/Crust/Template/img/Reports.png" height="24px" width="22px"/></span></td>';
                    $output .= '<td><input type="hidden" value="'.Validation::ObscureNumber($c['twitterid'],SALT_TWO).'"/><span class="delete icon" data-tip="Remove">X</span></td>';
                    $output .= '</tr>';
                }
                
                $output .= '</table>';
            }
            else 
            {
                $output = '<p>To add users to your fakers list use the search box above.</p>';
            }
            
            return $output;
        }
        
        public function _CheckValidity($userid)
        {
			if ($_SESSION['type']==0)
			{
				$valid = false;
				$message = '';
				
				$count = PaymentRequests::CountValidRecords($userid);
				
				//$this->errorschutney->PrintArray($count);
				
				if ($count)
				{
					$validdate = PaymentRequests::GetValidDate($userid);
					
					//$this->errorschutney->DebugArray($validdate);
					
					if ($validdate[0]>time())
					{
						$valid = true;
						$_SESSION['type'] = $validdate[1];
					}
					else
					{
						$_SESSION['type'] = 0;
						$message = Build::PageMessage('alert',array('You need to purchase a new <a href="'.$this->routechutney->HREF('/Payments/Details',$this->mod_rewrite).'">subscription</a> to continue using the Fakers Dashboard.'));
					}
				}
			}
			else
			{
				$valid = true;
			}
			
            return array($valid,$message);    
            
        }
	
		protected function _GetAutoRemoveForm($userid)
		{
			$isautoremove = $this->dbbind->CountAutoRemoveRecords($userid);
			
			if ($isautoremove)
			{
				$input = '<input type="button" id="autoremoveOff" value="Turn Off"/>';
			}
			else
			{
				$input = '<input type="button" id="autoremoveON" value="Turn On"/>';
			}
			
			$output = '<form id="autoremoveform"><fieldset>'.$input.'</fieldset></form>';
			
			return $output;
		}
	
		public function _BuildMenu()
		{
			$menu = '<ul><li><a href="https://statuspeople.com"><span class="ico3">&</span> Website</a></li>
			<li><a href="https://blog.statuspeople.com"><span class="ico3">%</span> Blog</a></li></ul>';
		
			return $menu;
		}
	
		public function EmailTest()
		{
			$headers['from'] = 'StatusPeople <info@statuspeople.com>';
			$headers['reply'] = 'info@statuspeople.com';
			$headers['return'] = 'info@statuspeople.com';
			
			$email = 'benj.christensen01@gmail.com';
			
			$message = '<p>Dear Rob</p><p>Hello World!!</p><p>Cheers, StatusPeople</p>';
			
			Email::SendEmail($email,'StatusPeople',$message,$headers);
		}
        
		public function Server()
		{
			$this->errorschutney->PrintArray($_SERVER['REQUEST_URI']);
			$this->errorschutney->DebugArray($_SERVER);
		}
	
}

?>