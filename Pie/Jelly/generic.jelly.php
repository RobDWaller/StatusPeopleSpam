<?php

use Controllers\AbstractController;

class Generic extends AbstractController
{
	
	public function Index()
	{
		
		self::Logout();
			
	}

        public function Logout($vars = null)
	{
		
                $mess = 1;
                
                if (!empty($vars[0]))
                {
                    $mess = $vars[0];
                }
            
		Sessions::DestroySessions();
		//echo $_COOKIE['hubtab'];
		//$this->errorschutney->DebugArray($_COOKIE);
		Sessions::DestroyCookies($_COOKIE);
		
                header('Location:'.$this->routechutney->BuildUrl('/Fakers/V/1',$this->mod_rewrite));
                
                die();
		
	}    
        
	public function _IsLogin()
	{
        
		setcookie('lastpage',$_SERVER['REQUEST_URI'],time()+300,'/');
		
        if ($_SESSION['userid'] < 1)
		{
                    self::Logout();
		}
                            
	}
	
	public function _LastPage()
	{
		if (isset($_COOKIE['lastpage']))
		{
			header('Location:'.$this->routechutney->BuildUrl($_COOKIE['lastpage'],$this->mod_rewrite));
			die();
		}
	}
        
        public function _SubscriptionValidity()
        {
            $check = strtotime('-5 Days');
            
            if ($_SESSION['Valid'] <= $check)
            {
                header('Location:'.$this->routechutney->BuildUrl('/Products/Subscriptions',$this->mod_rewrite));
            }
        }
        
        public function _Access($pageaccess)
        {
            
            $useraccess = $_SESSION['Access']; 
            
            if ($pageaccess == 1)
            {
                if ($useraccess != 1)
                {
                    header('Location:'.$this->routechutney->BuildUrl('/User/Home',$this->mod_rewrite));
                }
            }
            elseif ($pageaccess == 2)
            {
                if ($useraccess != 1 && $useraccess != 2 && $useraccess != 4 && $useraccess != 5)
                {
                    header('Location:'.$this->routechutney->BuildUrl('/Reports',$this->mod_rewrite));
                }
            }
            
        }
	
	public function _BuildMenu()
	{
		
                if ($_SESSION['Access'] == 1 || $_SESSION['Access'] == 2 || $_SESSION['Access'] == 4 || $_SESSION['Access'] == 5)
                {
                    $menuarray['Dashboard'] = $this->routechutney->HREF('/User/Home',$this->mod_rewrite);
                    $menuarray['Social Hub'] = $this->routechutney->HREF('/SocialMedia/Hub',$this->mod_rewrite);
                }
                
                $menuarray['Reports'] = $this->routechutney->HREF('/Reports',$this->mod_rewrite);
                
                if ($_SESSION['Access'] == 1 || $_SESSION['Access'] == 2 || $_SESSION['Access'] == 4 || $_SESSION['Access'] == 5)
                {
                    $menuarray['Settings'] = $this->routechutney->HREF('/User/MyDetails',$this->mod_rewrite);
                }
                
		$menuarray['Help'] = $this->routechutney->HREF('/Help',$this->mod_rewrite);
		$menuarray['Logout'] = $this->routechutney->HREF('/Generic/Logout',$this->mod_rewrite);
		
		$menu = $this->buildchutney->Menu($menuarray);	
		
		return $menu;
		
	}
	
	public function _AccountHashToID($accounthash)
	{

		$aid = $this->dbbind->GetAccountIDByHash($accounthash);
                
		return $aid[0];	
		
	}
	
	public function _SideBar($redirect = '/User/Home')
	{
		
                $sidebar .= self::_AccountSelection($redirect);
                $sidebar .= self::_QuickLinks();
                $sidebar .= self::_GetTip();
		
                $sidebar .= self::_GiveFeedback();		
				
		return $sidebar;
		
	}
	
	public function _Minions()
	{
		
                //echo $_SESSION['AccountID'];
            
                $minions = $this->dbbind->GetMinionPopulation($_SESSION['AccountID']);
		
                //$this->errorschutney->DebugArray($minions);
                
                $mood = $this->reportsbind->GetMinionScore($_SESSION['AccountID']);
                
		$moodtext = self::_MinionMood($mood[0]);
		
		$output = '<div class="row" id="minionsbox"><h2>Minions</h2>';
		$output .= '<p><img src="/Pie/Crust/Template/img/minions-3-blue.png" id="minionimg" /><span id="minioncount">: '.$minions.'</span></p>';
		$output .= '<p>Mood: <a href="'.$this->routechutney->HREF('/Reports/Minions',$this->mod_rewrite).'">'.$moodtext.'</a></p>';
		$output .= '<small><a href="'.$this->routechutney->HREF('/Help/Minions',$this->mod_rewrite).'">Find out more</a></small>';
		$output .= '</div>';
		
		return $output;		
	}
	
	public function _SttsplUrlGenerator()
	{
		$shorturlform = array('sbaccounthash'=>array('sbaccounthash','Hidden','',$_SESSION['AccountHash']),
			'sbshorturl'=>array('Url','Text'),
			'sbshorturlgenerate'=>array('Generate','Submit'));
		
		$shorturl = '<div class="row"><h2>Short Url</h2>';
		$shorturl .= '<script type="text/javascript" src="/Pie/Crust/Template/js/shorturl.js"></script>';
		$shorturl .= '<div id="sburlresult"></div>';
		$shorturl .= $this->formschutney->FormBuilder('','',$shorturlform);
		$shorturl .= '</div>';
		
		return $shorturl;
	}
	
	public function _BreadCrumbs($parents = null, $page)
	{
		
		$output = '<a href="'.$this->routechutney->HREF('/User/Home',$this->mod_rewrite).'">Dashboard</a> &gt; ';
		
		if (isset($parents))
		{
			
			foreach($parents as $key => $obj)
				
				$output .=  '<a href="'.$this->routechutney->HREF($obj,$this->mod_rewrite).'">'.$key.'</a> &gt; ';
		}
		
		$output .= $page;
		
		return $output;
		
	}
	
	public function _CheckForAuthenticationDetails()
	{
		
		$result = 0;
		
                $twitter = $this->dbbind->CountTwitterDetailsByAccountID($_SESSION['AccountID']);
                
                $facebook = $this->dbbind->CountFacebookDetailsRecords($_SESSION['AccountID']);
                
		if ($twitter > 0 || $facebook > 0)
		{
			$result = 1;	
		}
		
		return $result;
		
	}
	
	public function _AuthenticateTwitter($accounthash,$accountid)
	{
		
		$output = self::_AuthenticateWithTwitterForm($accounthash,$accountid);
		
		return $output;	
		
	}
	
        public function _AuthenticateWithFacebook($accounthash,$accountid)
	{
		
		$output = self::_AuthenticateWithFacebookForm($accounthash,$accountid);
		
		return $output;
	}
        
	public function _AuthenticateWithTwitterForm($accounthash,$accountid)
	{
		
                if ($_SESSION['Twitter'] == 0)
                {

                        $fields = array('Authenticate'=>array('authenticate','Submit'));

                        $output .= '<form id="twitterauthenticateform" method="post" action="'.$this->routechutney->BuildUrl('/SocialMedia/AuthenticateTwitter',$this->mod_rewrite).'">';
                        $output .= '<fieldset>';
                        $output .= '<input type="hidden" name="aid" value="'.$accounthash.'" />';
                        $output .= '<input type="hidden" name="ru" value="'.$this->routechutney->HREF('/SocialMedia/TwitterSuccess',$this->mod_rewrite).'" />';
                        $output .= '<input type="hidden" name="var1" value="'.$this->validationchutney->ObscureNumber($accountid).'" />';
                        $output .= '<input type="hidden" name="var2" value="2" />';
                        $output .= '<input type="Submit" name="authenticate" value="Connect" class="formbutton" />';
                        $output .= '</fieldset>';
                        $output .= '</form>';
                        

                }
                else
                {
                    $output .= '<p><strong>Connected</strong></p>';
                }
                
		return $output;
		
	}
	
	public function _AuthenticateWithFacebookForm($accounthash,$accountid)
	{
		//$result = self::CheckForFacebookDetails();
		                
                if ($_SESSION['Facebook'] == 0)
                {
                        $output .= '<form id="facebookauthenticateform" method="post" action="'.$this->routechutney->BuildUrl('/SocialMedia/AuthenticateWithFacebook',$this->mod_rewrite).'">';
                        $output .= '<fieldset>';
                        $output .= '<input type="hidden" name="aid" value="'.$accounthash.'" />';
                        $output .= '<input type="hidden" name="ru" value="'.$this->routechutney->HREF('/SocialMedia/FacebookSuccess',$this->mod_rewrite).'" />';
                        $output .= '<input type="hidden" name="var1" value="'.$this->validationchutney->ObscureNumber($accountid).'" />';
                        $output .= '<input type="hidden" name="var2" value="2" />';
                        $output .= '<input type="Submit" name="authenticate" value="Connect" class="formbutton" />';
                        $output .= '</fieldset>';
                        $output .= '</form>';
                }
                else
                {

                        $check = $this->dbbind->CheckForFacebookPage($_SESSION['AccountID']);

                        if ($check == 0)
                        {
                                $output = '<div class="row"><h2>Facebook</h2>';
                                $output .= '<p><a href="'.$this->routechutney->BuildUrl('/SocialMedia/FacebookApps',$this->mod_rewrite).'">Choose Facebook page to manage</a></p>';
                                $output .= '</div>';	
                        }
                        else
                        {
                            $output .= '<p><strong>Connected</strong></p>';
                        }
                }
		
                  
                    
		return $output;
	}
        
        public function _SetTwitterFacebookSessions($accountid)
        {
            $twitter = $this->dbbind->GetTwitterDetails($accountid);
            $twitterbio = $this->dbbind->GetTwitterBio($accountid);
            
            //$this->errorschutney->DebugArray($twitterbio);
            
            Sessions::GenerateSessions(array('TwitterID'=>$twitter[0],'TwitterToken'=>$twitter[1],'TwitterSecret'=>$twitter[2],'TwitterImage'=>$twitterbio[0]));
                
            $fbdetails = $this->dbbind->GetFacebookDetails($accountid);
                
            Sessions::GenerateSessions(array('fb_user_id'=>$fbdetails[0],'fb_access_token'=>$fbdetails[3],'fb_page_id'=>$fbdetails[1],'fb_page_access_token'=>$fbdetails[2]));
        }
        
        public function _AddTwitterBio($accountid)
	{
		
		$details = $this->dbbind->GetTwitterDetails($accountid);
		
                $user = $this->twitterbind->GetUserByID($details[1],$details[2],$details[0]);
                
                //$this->errorschutney->DebugArray($user);
                
		$profileimageurl = $this->validationchutney->SanitizeString($user->profile_image_url);
		$screenname = $this->validationchutney->SanitizeString($user->screen_name);
		$name = $this->validationchutney->SanitizeString($user->name);
		$description = $this->validationchutney->SanitizeString($user->description);
		$location = $this->validationchutney->SanitizeString($user->location);
		$date = time();
                
                $this->dbbind->AddTwitterBio($accountid,$profileimageurl,$screenname,$name,$description,$location,$user->statuses_count,$user->followers_count,$date);
		
	}

        protected function _GiveFeedback()
	{
		$output = '<div class="row"><h2>Feedback</h2>';	
		$output .= '<p>Want to tell us how to improve things or noticed a bug? Let us know and send us some <a href="'.$this->routechutney->HREF("/Help/Feedback",$this->mod_rewrite).'">feedback</a>. Your thoughts and ideas will be greatly appreciated.</p>';	
		$output .= '</div>';
		
		return $output;
	}
	
	protected function _GetTip()
	{
		$rand = rand(0,5);
		
		$tips = self::_Tips();
		
                $output = '<div class="row">';
		$output .= '<h2>Tip</h2>';
		$output .= '<p>';
		$output .= $tips[$rand];
		$output .= '</p>';
                $output .= '</div>';
                
		return $output;	
	}
        
        protected function _QuickLinks()
        {
            $links = array('<a href="/SocialMedia/Hub">Social Hub</a>: Manage Twitter, Facebook and RSS Feeds.',
                '<a href="/User/MyDetails">My Details</a>: Edit your user details.',
                '<a href="/Help">Help</a>: Learn more about StatusPeople Tools.',
                '<a href="/Help/Sitemap">Sitemap</a>: Find your way around our site.');
            
            $output = '<div class="row">';
            $output .= '<h2>Quick Links</h2>';
            
            foreach ($links as $link)
            {
                $output .= '<p>';
                $output .= $link;
                $output .= '</p>';
            }
            
            $output .= '</div>';
            
            return $output;
        }

        protected function _Tips()
	{
	
		$tips[] = 'Use our <a href="'.$this->routechutney->HREF('/SocialMedia/Webtools',$this->mod_rewrite).'">Web Tools</a> to display and track Social Media content on your website.';
		$tips[] = 'Use our <a href="'.$this->routechutney->HREF('/SocialMedia/Hub',$this->mod_rewrite).'">Social Hub</a> to manage your Social Networks and RSS Feeds.';
		$tips[] = 'Use our <a href="'.$this->routechutney->HREF('/Reports',$this->mod_rewrite).'">Reports Suite</a> to track and analyse your Social Media content.';
		$tips[] = 'Need help? <a href="'.$this->routechutney->HREF('/Help/FAQs',$this->mod_rewrite).'">Read our FAQs</a>.';
		$tips[] = 'If you need help getting started read our <a href="'.$this->routechutney->HREF('/Help/GetStarted',$this->mod_rewrite).'">Get Started Guide</a>.';
                $tips[] = 'Need to authenticate with Facebook or Twitter go to our <a href="'.$this->routechutney->HREF('/User/Authentication',$this->mod_rewrite).'">Authentication page</a>.';
                
		return $tips;	
		
	}
	
	protected function _MinionMood($mood)
	{
		if ($mood > 4.5)
		{
			$output = 'Jubilant';	
		}		
		elseif ($mood > 3 && $mood <= 4.5)
		{
			$output = 'Happy';	
		}
		elseif ($mood <= 3 && $mood > 1.5)
		{
			$output = 'Content';	
		}
		elseif ($mood <= 1.5)
		{
			$output = 'Sad';	
		}
		
		return $output;
		
	}
        
        public function _AccountSelection($redirect)
        {
            $useraccountscount = $this->dbbind->CountUserAccounts($_SESSION['UserID']);
            
            $accounttitle = $_SESSION['AccountTitle'];
            
            //$output = '<div class="row top"><h2>Account</h3>';
            
            if (!empty($accounttitle))
            {
                if ($_SESSION['Twitter'] == 1)
                {
                    $output.= '<img src="'.$_SESSION['TwitterImage'].'" height="30" width="30" />';
                }
                elseif ($_SESSION['Facebook'] == 1)
                {
                    $output.= '<img src="http://graph.facebook.com/'.$_SESSION['fb_page_id'].'/picture" height="30" width="30" />';
                }
                
                if ($useraccountscount == 1)
                {
                    $output .= '<p id="accountname">'.$accounttitle.'</p>';
                }
            }
            else 
            {
                $output .= '<p id="accountname"><a href="'.$this->routechutney->HREF('/User/Accounts',  $this->mod_rewrite).'">Please Name Your Account</a></p>';
            }
            
            if ($useraccountscount > 1)
            {
                $useraccounts = $this->dbbind->GetUserAccounts($_SESSION['UserID']);

                $accounts = self::_ProcessUserAccounts($useraccounts);

                $output .= '<form name="changeaccountform" id="changeaccountform" action="'.$this->routechutney->BuildUrl('/User/SwitchAccount',$this->mod_rewrite).'" method="post">';
                $output .= '<input type="hidden" name="redirect" id="redirect" value="'.$redirect.'" />';
                $output .= '<select name="account" id="account" class="accountselection">';
                foreach ($accounts[0] as $a)
                {
                    $output .= '<option value="'.$a[0].'"'.($a[0]==$accounts[1]?' SELECTED':'').'>'.$a[1].'</option>';
                }
                $output .= '</select>';
                $output .= '<input type="submit" name="accountchange" id="accountchange" class="microbutton" value="Change" />';
                $output .= '</form>';
                
//                $form = array('redirect'=>array('','Hidden','',$redirect),
//                                'account'=>array('account','NamelessDatalist',$accounts[0],'','',$accounts[1]),
//                                'accountselectionsubmit'=>array('Change','Submit'));
//                
//                $output .= $this->formschutney->FormBuilder('changeaccountform',$this->routechutney->BuildUrl('/User/SwitchAccount',$this->mod_rewrite),$form);
            }
            
            //$output .= '</div>';
            
            return $output;
        }
        
        protected function _ProcessUserAccounts($accounts)
        {
           
            
            if (!empty($accounts))
            {
                $i = 0;
                
                foreach ($accounts as $account)
                {
                    $data[$i][0] = $this->validationchutney->ObscureNumber($account['id']);
                    
                    if ($account['id'] == $_SESSION['AccountID'])
                    {
                        $obs = $data[$i][0];
                    }
                    
                    if (empty($account['name']))
                    {
                        $data[$i][1] = 'Unamed Account '.$i;
                    }
                    else
                    {
                        $data[$i][1] = $account['name'];
                    }
                    
                    $i++;
                }
                
                return array($data,$obs);
                                
            }
            
        }
	
}

?>