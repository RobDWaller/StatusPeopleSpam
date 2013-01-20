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
                if ($vars['q']=='iasd873PPOk98')
                {
//                    $_SESSION['userid'] = 41901771;
#                    $_SESSION['userid'] = 14859772;
                    $_SESSION['userid'] = 31386162;
                    
                    if (isset($_SESSION['message']))
                    {
                        $data['message'] = $_SESSION['message'];
                    }
                    elseif ($vars[0]==1) 
                    {
                        $data['message'] = $this->buildchutney->PageMessage('alert',array('Please connect to you Twitter account to make use of this service.'));
                    }

                    $data['homelink'] = $this->routechutney->HREF('/Fakers',$this->mod_rewrite);	
                    $data['title'] = 'Status People Fake Follower Check &mdash; Social Media Management Platform for Business';

                    $spamrecords = $this->dbbind->GetLatestSpamRecords(3);

                    //$this->errorschutney->DebugArray($spamrecords);

                    $data['spamrecords'] = $this->_BuildSpamRecords($spamrecords);    

                    $this->sessionschutney->UnsetSessions(array('message'));

                    $this->glaze->view('Spam/index.php',$data);
                }
                else
                {
                    $this->glaze->view('Spam/maintenance.php',$data);
                }
        }
        
        public function Scores($vars)
        {
            
                Generic::_IsLogin();
               
                $validity = $this->_CheckValidity($_SESSION['userid']);

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

                    $data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
                    $data['title'] = 'Status People Fake Follower Check &mdash; Social Media Management Platform for Business';
                    $data['twitterid'] = $_SESSION['userid'];

                    $fields = array('email'=>array('Email','Text','',$_SESSION['email']),
                                'title'=>array('Title','Title','',$_SESSION['title']),
                                'firstname'=>array('First Name','Text','',$_SESSION['firstname']),
                                'lastname'=>array('Last Name','Text','',$_SESSION['lastname']),
                                'submit'=>array('Proceed','Submit')); 

                    $data['form'] = $this->formschutney->FormBuilder('detailsform',$this->routechutney->BuildUrl('/Payments/ProcessDetails',$this->mod_rewrite),$fields);

                    $url = $this->routechutney->HREF('/API/GetTwitterBio?rf=json&twid='.$_SESSION['userid'],$this->mod_rewrite);

                    if (isset($vars[5]))
                    {
                        $data['twitterhandle'] = $vars[5];
                    }
                    else 
                    {

                        $bio = $this->curlbind->GetJSON($url);

                        $data['twitterhandle'] = $bio->data->screenname;

                    }

                    $this->sessionschutney->UnsetSessions(array('message'));

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

            if ($validity[0])
            {

                $userid = $_SESSION['userid'];

                $details = $this->dbbind->GetTwitterDetails($userid);
                
//                $verify = $this->twitterbind->Verify($details[2],$details[3]);
                
//                $this->errorschutney->DebugArray($verify);
                
                $url = $this->routechutney->HREF('/API/GetTwitterBio?rf=json&twid='.$userid,$this->mod_rewrite);

                $bio = $this->curlbind->GetJSON($url);
                
//                $this->errorschutney->DebugArray($bio);

                $data['twitterhandle'] = $bio->data->screenname;

                $count = $this->dbbind->CheckForFakerCheck($userid,$userid);
                
                $data['firsttime'] = 0;
                
                if (!$count)
                {
                    $this->dbbind->AddFakerCheck($userid,$userid,$bio->data->screenname,$bio->data->image,1,time(),time());

                    $spam = $this->dbbind->GetSpamDetails($userid);
                    
                    $this->dbbind->AddFakerCheckScore($userid,$bio->data->screenname,$spam[2],$spam[3],$spam[4],$spam[5],time());
                
                    $data['firsttime'] = 1;
                }

//                $userid = 31386162;

                $competitors = $this->dbbind->GetCompetitors($userid);
                $fakes = $this->dbbind->GetFakes($userid,5);

//            $this->errorschutney->PrintArray($competitors);
//            $this->errorschutney->DebugArray($fakes);

                $data['competitors'] = $this->_BuildCompetitors($competitors);
                $data['fakes'] = $this->_BuildFakes($fakes);

                $data['twitterid'] = $userid;

                $this->glaze->view('Spam/advanced.php',$data); 
            }
            else
            {
                header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));
                die();
            }
            
        }
        
        public function Reset()
        {
            Generic::_IsLogin();
            
            if (isset($_SESSION['message']))
            {
                $data['message'] = $_SESSION['message'];
            }
            
            $this->sessionschutney->UnsetSessions(array('message'));

            $this->glaze->view('Spam/reset.php',$data);
        }

        public function ResetConnectionDetails()
        {
            Generic::_IsLogin();
            
            $userid = $_SESSION['userid'];
            
            $reset = $this->dbbind->ResetTwitterDetails($userid);
            
            if ($reset)
            {
                $_SESSION['message'] = $this->buildchutney->PageMessage('success',array('Connection Details Reset Successfully. Please now reconnect to the Fakers App.'));
                
                header('Location:'.$this->routechutney->BuildUrl('/',$this->dbbind));
            }
            else
            {
                $_SESSION['message'] = $this->buildchutney->PageMessage('failure',array('Failed to reset connection details. Please contact info@statuspeople.com'));
                
                header('Location:'.$this->routechutney->BuildUrl('/Fakers/Reset',$this->dbbind));
            }
        }
        
        public function FindOutMore()
        {
            $data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
            $data['title'] = 'Status People Fake Follower Check &mdash; Social Media Management Platform for Business';
            
            $this->glaze->view('Spam/info.php',$data);
        }
        
        public function Terms()
        {
            $data['homelink'] = $this->routechutney->HREF('/Fakers/Scores',$this->mod_rewrite);	
            $data['title'] = 'Status People Fake Follower Check &mdash; Social Media Management Platform for Business';
            
            $this->glaze->view('Spam/terms.php',$data);
        }
        
        public function Wall()
        {
            $data['homelink'] = $this->routechutney->HREF('/',$this->mod_rewrite);	
            $data['title'] = 'Status People Fake Follower Check &mdash; Social Media Management Platform for Business';
            
            $spamrecords = $this->dbbind->GetLatestSpamRecords(51);
            
            $data['spamrecords'] = $this->_BuildSpamRecords($spamrecords);  
            
            $this->glaze->view('Spam/fakers.php',$data);
        }
        
        public function Goodies($vars)
        {
            if ($vars['q']=='78asoy8_op789')
            {
                $data['homelink'] = $this->routechutney->HREF('/',$this->mod_rewrite);	
                $data['title'] = 'Status People Fake Follower Check &mdash; Social Media Management Platform for Business';

                $members = $this->dbbind->Get500kClub();

                $data['goodies'] = $this->_Build500kClub($members);  

                $this->glaze->view('Spam/goodies.php',$data);
            }
        }
        
        public function Extend()
        {
            $oldtime = 1355180905;
            
            $newtime = strtotime(date('Y/m/d',$oldtime).' +1 Months');
            
            $this->errorschutney->PrintArray($oldtime);
            $this->errorschutney->PrintArray($newtime);
            $this->errorschutney->PrintArray(date('Y/m/d',$newtime));
        }
        public function GetScores()
        {
            
            Generic::_IsLogin();
            
            $userid = $_SESSION['userid'];
            $search = $_POST['name'];

            $url = $this->routechutney->HREF('/API/GetSpamScores?rf=json&usr='.$userid.'&srch='.$search,$this->mod_rewrite);
            
            $scores = $this->curlbind->GetJSON($url);
       
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
	
	public function TwitterSuccess($vars)
	{
            
            if ($vars['rsp'] == 400)
            {
                $_SESSION['Twitter'] = 1;
                $data['message'] = $this->buildchutney->PageMessage('failure',array("There was an error authenticating with Twitter. Please try again, if this problem persists contact info@statuspeople.com."));
                header('Location:'.$this->routechutney->BuildUrl('/Fakers',$this->mod_rewrite));
            }
            elseif ($vars['rsp'] == 200)
            {

                $userid = $vars['ui'];
                $token = $vars['oat'];
                $secret = $vars['oas'];
                $where = $vars['var1'];
                
                $_SESSION['userid'] = $vars['ui'];
                $_SESSION['token'] = $vars['oat'];
                $_SESSION['secret'] = $vars['oas'];

                //$this->errorschutney->DebugArray($vars);

                $exists = $this->dbbind->CountUsers($userid);
                
                $ok = false;
                
                if ($exists == 1)
                {
                    $ok = true;
                }
                else 
                {
                    $bio = $this->twitterbind->GetUserByID($token,$secret,$userid);
                    
                    //$this->errorschutney->DebugArray($bio);
                    
                    $result = $this->dbbind->AddTwitterDetails($userid,$token,$secret,time());
                    
                    if ($result > 0)
                    {
                        $ok = true;
                    }
                    
                    $countinfo = $this->dbbind->CountUserInfoRecords($userid); 
                    
                    if ($countinfo==0)
                    {
                        $this->dbbind->AddUserInfo($userid,$bio->screen_name,$bio->profile_image_url,time(),time());
                    }
                        
                }
                
                //$result = 1;
                
                $this->sessionschutney->UnsetSessions(array('returnurl','var1','oauth_token_secret'));
                
                if ($ok)
                {
                    //$_SESSION['message'] = $this->buildchutney->PageMessage('success',array('Twitter successfully authenticated.'));
                    
                    header('Location:'.$this->routechutney->BuildUrl('/Fakers/Scores',$this->mod_rewrite));   
                }
                else
                {
                    $_SESSION['message'] = $this->buildchutney->PageMessage('failure',array("There was an error with the Twitter authentication process. Please try again, if this problem persists contact info@statuspeople.com."));
                    header('Location:'.$this->routechutney->BuildUrl('/Fakers',$this->mod_rewrite));
                }
            }
				
	}
	
	public function AuthenticateTwitter()
	{
		
            $_SESSION['returnurl'] = $_POST['ru'];
            $_SESSION['var1'] = $_POST['var1'];
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

            $request_token = $this->twitter->getRequestToken(OAUTH_CALLBACK);

            /* Save temporary credentials to session. */

            $_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

            /* If last connection failed don't display authorization link. */
            switch ($this->twitter->http_code) {
                case 200:
                    /* Build authorize URL and redirect user to Twitter. */
                    $url = $this->twitter->getAuthorizeURL($token,FALSE);
                    header('Location: ' . $url); 
                    break;
                default:
                    /* Show notification if something went wrong. */
//                                    $_SESSION['message'] = $this->buildchutney->ErrorMessages(array('Could not connect to Twitter. Refresh the page or try again later.'));
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
		
		$this->sessionschutney->UnsetSessions(array('oauth_token','oauth_token_secrect'));

		/* If HTTP response is 200 continue otherwise send to connect page to retry */
		if (200 == $this->twitter->http_code) {
                    /* The user has been verified and the access tokens can be saved for future use */
                    $_SESSION['status'] = 'verified';
                    $redirect = $_SESSION['returnurl'].'?ui='.$access_token['user_id'].'&oat='.$access_token['oauth_token'].'&oas='.$access_token['oauth_token_secret'].'&rsp=200&var1='.$_SESSION['var1'];
                    header('Location:'.$redirect);
                } 
                else 
                {
                    /* Save HTTP status for error dialog on connnect page.*/
                    $_SESSION['message'] = $this->buildchutney->PageMessage('failure',array('Failed to connect to Twitter please try again.'));
                    $redirect = $this->routechutney->BuildUrl('/Fakers/ClearTwitterSessions',$this->mod_rewrite);
                    header('Location:'.$redirect);
                }
		
	}
	
	public function ClearTwitterSessions()
	{
		
                $this->sessionschutney->UnsetSessions(array('status','oauth_token','oauth_token_secret','access_token','oauth_status'));

                header('Location:'.$_SESSION['returnurl'].'?rsp=400');
		
	}
	
        protected function _BuildSpamScores($scores)
        {
            
            $spam = round(($scores[4]/$scores[2])*100,0);
            $potential = round(($scores[3]/$scores[2])*100,0);
            
            $output = '<div class="row" id="scoresholder">';
            $output .= '<div class="three a">';
            $output .= '<h1 class="red">Fake</h1>';
            $output .= '<h2 class="red">'.$spam.'%</h2>';
            $output .= '</div>';
            $output .= '<div class="three a">';
            $output .= '<h1>Inactive</h1>';
            $output .= '<h2>'.$potential.'%</h2>';
            $output .= '</div>';
            $output .= '<div class="three red">';
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
                
                if ($i==$k)
                {
                    $output .= '<div class="row">';
                    $k+=3;
                }
                
                $output .= '<div class="three'.($i<($k-1)?' a':'').'">';
                $output .= '<img src="'.$r['avatar'].'" height="28px" width="28px" />';
                $output .= '<span class="spamscore"><a href="http://twitter.com/'.$r['screen_name'].'" target="_blank">'.$r['screen_name'].':</a> <span class="red">'.$spam.'% Fake</span></span>';
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
                $output .= '<span class="spamscore"><a href="http://twitter.com/'.$r['screen_name'].'" target="_blank">'.$r['screen_name'].':</a> <span class="green">'.$spam.'% Fake</span></span>';
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
        
        protected function _BuildFakes($fakes)
        {
            if (!empty($fakes))
            {
                
                $output = '<ul class="fakeslist">';
                
                foreach ($fakes as $f)
                {
                    $output .= '<li><input type="hidden" value="'.$f['screen_name'].'" class="sc" /><input type="hidden" value="'.$f['twitterid'].'" class="ti"/><img src="'.$f['avatar'].'" width="48px" height="48px" /> '.$f['screen_name'].'<small><a href="#details" class="details">Details</a> | <a href="#block" class="block">Block</a> | <a href="#spam" class="notspam">Not Spam</a></small></li>';
                }
                
                $output .= '</ul>';
            }
            else 
            {
                $output = '<div id="checkform"><p>No Fake Followers found at this time.</p><p><fieldset><input type="button" id="checkfakes" value="Check For New Fakes"/></fieldset></p></div>';
            }
            
            return $output;
        }
        
        protected function _BuildCompetitors($competitors)
        {
            if (!empty($competitors))
            {
                
                $output = '<table class="competitorlist">';
                
                foreach ($competitors as $c)
                {
                    $fake = round(($c['spam']/$c['checks'])*100);
                    $inactive = round(($c['potential']/$c['checks'])*100);
                    $good = (100-($fake+$inactive));
                    
                    $output .= '<tr>';
                    $output .= '<td><img src="'.$c['avatar'].'" width="48px" height="48px" /></td>';
                    $output .= '<td><span>'.$c['screen_name'].'</span></td>';
                    $output .= '<td><span class="red">Fake: '.$fake.'%</span></td>';
                    $output .= '<td><span class="orange">Inactive: '.$inactive.'%</span></td>';
                    $output .= '<td><span class="green">Good: '.$good.'%</span></td>';
                    $output .= '<td><input type="hidden" value="'.$c['twitterid'].'" class="ti"/><input type="hidden" value="'.$c['screen_name'].'" class="sc"/><span class="chart" title="View on chart"><img src="/Pie/Crust/Template/img/Reports.png"/></span></td>';
                    $output .= '<td><input type="hidden" value="'.$c['twitterid'].'"/><span class="delete" title="Remove">X</span></td>';
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
        
        protected function _CheckValidity($userid)
        {
            $valid = false;
            $message = '';
            
            $count = $this->paymentbind->CountValidRecords($userid);
            
            if ($count)
            {
                $validdate = $this->paymentbind->GetValidDate($userid);
                
                if ($validdate[0]>time())
                {
                    $valid = true;
                }
                else
                {
                    $message = $this->buildchutney->PageMessage('alert',array('You need to purchase a new <a href="'.$this->routechutney->HREF('/Payments/Details',$this->mod_rewrite).'">subscription</a> to continue using the Fakers Dashboard.'));
                }
            }
  
            return array($valid,$message);    
            
        }
        
}

?>