<?php

class Cron extends Jelly
{
    
    private $cronhash = '42c3fe3bb11e9014479a36f8faeff2469c8433178c99829f62f7f83d9d7d11eb';
	
	function __construct() {
        parent::__construct();
        
        ini_set('max_execution_time', 1800);
    }
    
    public function UpdateFakers()
    {
        if ($_POST['ch'] == $this->cronhash)
        {
            $records = $this->dbbind->GetLatestSpamRecords(1);
            
            foreach ($records as $r)
            {
                $this->curlbind->GetJSON('http://fakers.statuspeople.com/API/GetFakersScoreUpdate?rf=json&usr='.$r['twitterid'].'&srch='.$r['screen_name']);
            }
        }
    }
    
    public function UpdateFakersCheck()
    {
		//if (true)
		if ($_POST['ch'] == $this->cronhash)
        { 
            $users = $this->dbbind->GetCheckers();
			
			//$this->errorschutney->PrintArray($users);
		
			foreach ($users as $u)
			{
				$records = $this->dbbind->GetUserToCheck($u['userid']);
				
	            if (!empty($records))
				{
					//$this->errorschutney->DebugArray($records);
					
					foreach ($records as $r)
					{
						$this->dbbind->UpdateLastCheckTime($r['twitterid'],$r['screen_name'],time());
						
						$spam = array();
						
						$details = $this->dbbind->GetTwitterDetails($r['userid']);
						
						$search = $this->validationchutney->StripNonAlphanumeric($r['screen_name']);
				
						$bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);
						
							$gethundreds = API::_GetHundreds($search,$bio,$details,10);
							$hndrds = $gethundreds[0];
							$h = $gethundreds[1];
							$followers = $gethundreds[2];
	
							if (!empty($hndrds))
							{
								
								$chcks = API::_GetChecks($h,$hndrds,$followers);
								
								$c = 0;
								$sc = 0;
								$p = 0;
	
								foreach ($chcks as $ch)
								{
									if (!empty($hndrds[$ch]))
									{
										$fllwrs = false;
										$fcnt = 0;
	
										while(!$fllwrs)
										{
											if ($fcnt < 3)
											{
												$followerdetails = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$hndrds[$ch],100);
	
												if ($followerdetails['code'] == 200)
												{
													$fllwrs = true;
												}
												else if ($followerdetails['code'] == 502)
												{
													if ($fcnt==2)
													{
														$to = 'rdwaller1984@googlemail.com';
														$subject = 'StatusPeople Failed Fakers Cache -- Twitter Data Process Issue';
														$message = '<p>Dear Rob,<p><p>Twitter is currently struggling to process data so we are unable to cache data.</p><p>Thanks, StatusPeople</p>';
														$headers['from'] = 'StatusPeople <info@statuspeople.com>';
														$headers['reply'] = 'info@statuspeople.com';
														$headers['return'] = 'info@statuspeople.com';
	
														$this->emailchutney->SendEmail($to,$subject,$message,$headers);
													}
												}
	
	//                                            echo $fcnt;
	
												$fcnt++;
											}
											else
											{
												$fllwrs = true;
											}
	
										}
	
										if ($followerdetails['code'] == 200)
										{
	
			//                                $this->errorschutney->PrintArray($followerdetails);
	
											foreach ($followerdetails['data'] as $follower)
											{
	
												$faker = API::_GetFakerStatus($follower);
										
												if ($faker['status']==1)
												{
													$sc++;
													$spam[] = $faker['follower'];
												}
												elseif ($faker['status']==2)
												{
													$p++;
												}
												
												$c++;
											}
											
											
											$langs = API::_GetLanguageDetails($followerdetails['data'],$langs);
									
											$avg = API::_GetAverages($followerdetails['data'],$avg);
											
										}
									}
								}
	
								$results['followers']=$followers;
								$results['checks']=$c;
								$results['potential']=$p;
								$results['spam']=$sc;
	
								//$this->errorschutney->DebugArray($results);
								$langs = API::_ReorderLanguages($langs);
		//                        $rb = ($checks*100)-100;
		//                        $rt = $checks*100;
	
								API::_UpdateCache($r['userid'],$langs,$avg,array($spam[0],$spam[1]));
								
								if ($results['followers']>500)
								{
									$cks = $checks*100;
								}
								else
								{
									$cks = $results['followers'];
								}
	
								if ($results['checks']>=($cks-1))
								{
									$this->dbbind->AddCheckScore($bio['user']->id,$bio['user']->screen_name,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
									$update = $this->dbbind->UpdateUsersToCheckTime($r['twitterid'],$r['screen_name'],time());
								}
								else
								{
	#                                $to = 'rdwaller1984@googlemail.com';
	#                                $subject = 'StatusPeople Failed Fakers Cache';
	#                                $message = '<p>Dear Rob,<p><p>This is a missed fakers cache score...</p><pre>'.print_r($bio,true)..'</pre><p>Thanks, StatusPeople</p>';
	#                                $headers['from'] = 'StatusPeople <info@statuspeople.com>';
	#                                $headers['reply'] = 'info@statuspeople.com';
	#                                $headers['return'] = 'info@statuspeople.com';
	#
	#                                $this->emailchutney->SendEmail($to,$subject,$message,$headers);
									$this->dbbind->AddSpamError(print_r($bio,true),print_r($results,true),1,time());
								}
	
								$s = 0;
	
		//                        $this->errorschutney->PrintArray($spam);
	
								$insertstring = '';
	
								foreach ($spam as $spm)
								{
									if (!$r['autoremove'])
									{
										if ($s < 1000)
										{
											$insertstring .= '('.$bio['user']->id.','.$spm['id'].',"'.$spm['screen_name'].'","'.$spm['image'].'",'.time().'),';
										}
										$s++;
									}
									else
									{
										$insertstring .= '('.$bio['user']->id.','.$spm['id'].',"'.$spm['screen_name'].'","'.$spm['image'].'",'.time().'),';
									}
								}
	
								$is = substr($insertstring,0,-1);
	
		//                        $this->errorschutney->PrintArray($is);
	
								$this->dbbind->AddFakes($is);
	
							//}
					   
						}
						
						//$this->errorchutney->DebugArray($results);
						
						unset($spam);
						unset($chcks);
						unset($hndrds);
						unset($fids);
						unset($results);
						unset($langs);
						unset($avg);
					}
				}
			}
		}
//        else
//        {
//            echo 'No Match';
//        }
    }
    
    public function AutoRemoveSpam()
    {
		//if (true)
		if ($_POST['ch'] == $this->cronhash)
		{
            
            $users = $this->dbbind->GetAutoSpamUsers();
            
			//$this->errorschutney->PrintArray($users);
			
            if ($users)
            {
                
                foreach ($users as $u)
                {
                    $details = $this->dbbind->GetTwitterDetails($u['userid']);
                    
					//$this->errorschutney->PrintArray($details);
                    
                    $fakes = $this->dbbind->GetFakes($u['userid'],1,15);
                    
					if (!empty($fakes))
					{
						//$this->errorschutney->PrintArray($fakes);
                    	
						foreach ($fakes as $f)
						{
							$destroy = $this->twitterbind->DestroyFriendship($details[2],$details[3],$f['twitterid']);
							
							//$this->errorschutney->PrintArray($destroy);
							
							if ($destroy)
							{
								$block = $this->dbbind->BlockSpam($u['userid'],$f['twitterid']);
							}
						}
					}
                    $this->dbbind->UpdateAutoRemove($u['userid'],$u['userid'],time());
                }
                
            }
            
         } 
    }
    
	public function SendSubscriptionReminder()
	{
 		if ($_POST['ch'] == $this->cronhash)
        {
			$emails = $this->paymentbind->GetEmailList();
			
		//$this->errorschutney->PrintArray($emails);
		
			$headers['from'] = 'StatusPeople <fakers@statuspeople.com>';
			$headers['reply'] = 'fakers@statuspeople.com';
			$headers['return'] = 'fakers@statuspeople.com';
		
			if (!empty($emails))
			{
				foreach ($emails as $e)
				{
					
					if ($e['valid']<=strtotime('+2 Days')&&$e['valid']>strtotime('+1 Day'))
					{
						//echo '+1 Day';
						$this->errorschutney->PrintArray(array($e['email'],date('y/m/d h:i',$e['valid'])));
						$email = $e['email'];
						//$email = 'rdwaller1984@googlemail.com';
						
						$message = '<p>Hi '.$e['forename'].',</p>';
						$message .= '<p>We just thought we\'d let you know that your StatusPeople Fakers App Dashboard Subscription is about to expire.</p>';
						$message .= '<p>If you would like to renew please go to your <a href="'.$this->routechutney->HREF('/Payments/Subscriptions',$this->mod_rewrite).'">subscriptions page</a>.</p>';
						$message .= '<p>And if you have any thoughts and feedback on how we can improve things please let us know. Email us at info@statuspeople.com or tweet us at <a href="http://twitter.com/StatusPeople">@StatusPeople</a>.</p>';
						$message .= '<p>Cheers, StatusPeople</p>';
						
						$this->emailchutney->SendEmail($email,'StatusPeople Fakers Dashboard Subscription about to Expire',$message,$headers);
						
						$this->dbbind->AddEmailSend($email,'StatusPeople Fakers Dashboard Subscription about to Expire',time());
					}
					elseif ($e['valid']<=strtotime('-1 Day')&&$e['valid']>strtotime('-2 Days'))
					{
						//echo '-1 Day';
						$this->errorschutney->PrintArray(array($e['email'],date('y/m/d h:i',$e['valid'])));	
						
						$email = $e['email'];
						//$email = 'rdwaller1984@googlemail.com';
						
						$message = '<p>Hi '.$e['forename'].',</p>';
						$message .= '<p>We\'re sorry to say this but your StatusPeople Fakers App Dashboard Subscription has now expired.</p>';
						$message .= '<p>If you would like to continue using the Fakers Dashboard tools please <a href="'.$this->routechutney->HREF('/Payments/Subscriptions',$this->mod_rewrite).'">purchase a new subscription</a>.</p>';
						$message .= '<p>And if you have any thoughts and feedback on how we can improve things please let us know. Email us at info@statuspeople.com or tweet us at <a href="http://twitter.com/StatusPeople">@StatusPeople</a>.</p>';
						$message .= '<p>Cheers, StatusPeople</p>';
						
						$this->emailchutney->SendEmail($email,'StatusPeople Fakers Dashboard Subscription has Expired',$message,$headers);
						
						$this->dbbind->AddEmailSend($email,'StatusPeople Fakers Dashboard Subscription has Expired',time());
					}
					elseif ($e['valid']<=strtotime('-7 Days')&&$e['valid']>strtotime('-8 Days'))
					{
						//echo '-7 Days';
						$this->errorschutney->PrintArray(array($e['email'],date('y/m/d h:i',$e['valid'])));
						
						$email = $e['email'];
						//$email = 'rdwaller1984@googlemail.com';
						
						$message = '<p>Hi '.$e['forename'].',</p>';
						$message .= '<p>This is just to remind you that your StatusPeople Fakers App Dashboard Subscription has now expired.</p>';
						$message .= '<p>If you would like to continue using the Fakers Dashboard tools please <a href="'.$this->routechutney->HREF('/Payments/Subscriptions',$this->mod_rewrite).'">purchase a new subscription</a>.</p>';
						$message .= '<p>And if you have any thoughts and feedback on how we can improve things please let us know. Email us at info@statuspeople.com or tweet us at <a href="http://twitter.com/StatusPeople">@StatusPeople</a>.</p>';
						$message .= '<p>Cheers, StatusPeople</p>';
						
						$this->emailchutney->SendEmail($email,'StatusPeople Fakers Dashboard Subscription Expiry Reminder',$message,$headers);
						
						$this->dbbind->AddEmailSend($email,'StatusPeople Fakers Dashboard Subscription Expiry Reminder',time());
					}
					elseif ($e['valid']<=strtotime('-30 Days')&&$e['valid']>strtotime('-31 Days'))
					{
						//echo '-30 Days';
						$this->errorschutney->PrintArray(array($e['email'],date('y/m/d h:i',$e['valid'])));
						
						$email = $e['email'];
						//$email = 'rdwaller1984@googlemail.com';
						
						$message = '<p>Hi '.$e['forename'].',</p>';
						$message .= '<p>Just like to say goodbye and say thanks for using our Fakers Dashboard.</p>';
						$message .= '<p>But if you don\'t want to say goodbye just yet remember you can purchase a <a href="'.$this->routechutney->HREF('/Payments/Subscriptions',$this->mod_rewrite).'">new subscription</a> any time.</p>';
						$message .= '<p>We won\'t pester you with any more reminders but if you have any thoughts and feedback on how we can improve things please let us know. Email us at info@statuspeople.com or tweet us at <a href="http://twitter.com/StatusPeople">@StatusPeople</a>.</p>';
						$message .= '<p>Cheers, StatusPeople</p>';
						
						$this->emailchutney->SendEmail($email,'Goodbye from StatusPeople Fakers Dashboard',$message,$headers);
						
						$this->dbbind->AddEmailSend($email,'Goodbye from StatusPeople Fakers Dashboard',time());
					}
				}
			}
			
 		}
	}
	
/* 	public function GetStatusPeopleSubscriberDetails()
	{
		$details = $this->paymentbind->GetSubscriberDetails();
		
		$this->errorschutney->PrintArray($details);
	} */

/* 	public function TestEmailSend()
	{
		$email = 'test@test.com';
		
		$this->dbbind->AddEmailSend($email,'Goodbye from StatusPeople Fakers Dashboard',time());
	} */
	
	public function GetCheckersFromChecks()
	{
		if ($_POST['ch'] == $this->cronhash)
        {
			$data = $this->dbbind->GetCheckersFromChecks();
			
			//$this->errorschutney->PrintArray($data);
			
			if (!empty($data))
			{
				foreach ($data as $d)
				{
					$this->dbbind->AddChecker($d['userid'],time(),time());
				}
			}
		}
	}
	
	public function GetDeepDiveFollowerIDs()
	{
		$dives = $this->deepdivebind->GetDives();
		
		//$this->errorschutney->PrintArray($dives);
		
		foreach ($dives as $d)
		{
			$details = $this->dbbind->GetTwitterDetails($d['userid']);
			
			$idslist = $this->twitterbind->GetFollowerIDs($details[2],$details[3],$d['twitterid'],$d['twittercursor']);
			
			//$this->errorschutney->PrintArray($idslist);
			
			$ids = $idslist['data']->ids;
			$cursor = $idslist['data']->next_cursor_str;
			
			//$this->errorschutney->PrintArray($ids);
			//$this->errorschutney->PrintArray($cursor);
			
			$jsonids = json_encode($ids);
			
			$this->deepdivebind->AddFollowerIDs($d['twitterid'],$jsonids,time());
			$this->deepdivebind->UpdateCursor($d['userid'],$d['twitterid'],$cursor);
		}
	}
	
	public function GetDeepDiveFollowers()
	{
		$dives = $this->deepdivebind->GetDives();
		
		$this->errorschutney->PrintArray($dives);
		
		foreach ($dives as $d)
		{
			$followerids = $this->deepdivebind->GetFollowerIDs($d['twitterid']);
			
			$this->errorschutney->PrintArray($followerids);
			
			$fidlist = json_decode($followerids[2]);
			
			$h = 1;
			$r = 1;
			$k = 0;
			
			foreach ($fidlist as $f)
			{
				if ($r == 5)
				{
					$newarrays[$k][]=$f;
					$r = 1;
					$h++;
				}
				else
				{
					$r++;
				}
				
				if ($h>100)
				{
					$h=1;
					$k++;
				}
			}
			
			$details = $this->dbbind->GetTwitterDetails($d['userid']);
			
			foreach ($newarrays as $array)
			{
				$count = count($array);
				
				//$this->errorschutney->DebugArray($followerids[0]['id']);
				
				if ($count == 100)
				{
					$followers = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$array,100);
					
					$followers = json_encode($followers);
					
					$this->errorschutney->PrintArray(strlen($followers));
					
					$this->deepdivebind->AddFollowers($d['twitterid'],$followerids[0]['id'],$followers,time());
				}
			}
			
			$this->deepdivebind->UpdateFollowerIDsStatus($followerids[0]);
		}
	}
	
	public function GenerateDeepDiveScore()
	{
		$dives = $this->deepdivebind->GetDives();
		
		//$this->errorschutney->DebugArray($dives);
		
		foreach ($dives as $d)
		{
			$followers = $this->deepdivebind->GetFollowers($d['twitterid']);
			
			$c = 0;
			$sc = 0;
			$p = 0;
			
			foreach ($followers as $f)
			{
				$fols = json_decode($f['followers']);
				
				foreach ($fols->data as $fl)
				{
					//$this->errorschutney->DebugArray($fl);	
					
					$faker = API::_GetFakerStatus((array)$fl);
										
					if ($faker['status']==1)
					{
						$sc++;
						$spam[] = $faker['follower'];
					}
					elseif ($faker['status']==2)
					{
						$p++;
					}
					
					$c++;
				}
			}
			
			//$results['followers']=$followers;
			$results['checks']=$c;
			$results['potential']=$p;
			$results['spam']=$sc;
			
			$spam = round(($sc/$c)*100);
			
			$potential = round(($p/$c)*100);
			
			$good = (100-$spam)-$potential;
			
			$this->errorschutney->PrintArray($results);
			$this->errorschutney->PrintArray(array('spam'=>$spam,'potential'=>$potential,'good'=>$good));
			
		}
	}
	
/* 	public function ObsTest()
	{
		$nums = array(1,2,3,12,456,6753,73826,287364,7263718,90876543,543216789,1233213131,12332131319,123321313198,1233213131987,12332131319876);
		
		foreach ($nums as $n)
		{
			$data['num'] = $n;
			$data['ob1'] = $this->validationchutney->ObscureNumber($n,'hyfi138');
			$data['unob1'] = $this->validationchutney->UnobscureNumber($data['ob1'],'hyfi138');
			//$data['ob2'] = $this->validationchutney->ObscureNumber('####','6868768');
			//$data['unob2'] = $this->validationchutney->UnobscureNumber('+=/','6868768');
			//$data['ob3'] = $this->validationchutney->ObscureNumber($n,'');
			//$data['unob3'] = $this->validationchutney->UnobscureNumber($data['ob3'],'123123');
			
			$this->errorschutney->PrintArray($data);
		}
		
	}
	
	public function IndTest()
	{
		$string = 'asd78876kjhjkhwejkh9898723';
		$salt = 'ao3etycw';
		
		$obscure = $this->validationchutney->Obscure($string,$salt);
		$unobscure = $this->validationchutney->Unobscure($obscure,$salt);
		
		$this->errorschutney->DebugArray(array('string'=>$string,'salt'=>$salt,'obscure'=>$obscure,'unobscure'=>$unobscure));
	} */
}
?>