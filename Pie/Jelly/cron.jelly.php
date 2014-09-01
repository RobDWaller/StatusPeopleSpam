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
			
			//$children = $this->dbbind->GetAllChildren();
			
			//$this->errorschutney->PrintArray($children);
			
			//$usersdata = $this->_MergeUsersAndChildren($users,$children);
			
			//$this->errorschutney->DebugArray($usersdata);
		
			foreach ($users as $u)
			{
				$this->dbbind->UpdateCheckerTime($u['userid'],time());
				
				$records = $this->dbbind->GetUserToCheck($u['userid']);
				
				$this->errorschutney->PrintArray($records);
				
	            if (!empty($records))
				{
					//$this->errorschutney->DebugArray($records);
					
					foreach ($records as $r)
					{
						$this->dbbind->UpdateLastCheckTime($r['twitterid'],$r['screen_name'],time());
						
						$spam = array();
						
						$details = $this->dbbind->GetTwitterDetails($r['userid']);
						
						$search = Validation::StripNonAlphanumeric($r['screen_name']);
				
						$bio = $this->twitterbind->GetUserByID($details[2],$details[3],$r['twitterid']);
						//$bio = $this->twitterbind->GetUserByID($details[2],$details[3],171026460);
						
						
						$countinfo = $this->dbbind->CountUserInfoRecords($r['twitterid']); 
                    
						//$this->errorschutney->PrintArray($countinfo);
					
                        if ($countinfo>=1)
                        {
							//$this->errorschutney->PrintArray($countinfo);
							$upd1 = $this->dbbind->UpdateUserInfo($r['twitterid'],$bio['user']->screen_name,$bio['user']->profile_image_url);
							
							$this->errorschutney->PrintArray(array('Update1',$upd1,$bio['user']->profile_image_url));
						}
						
						//$this->errorschutney->DebugArray($bio);
						
						$upd2 = $this->dbbind->UpdateFakerCheck($r['twitterid'],$bio['user']->screen_name,$bio['user']->profile_image_url);
						
						$this->errorschutney->PrintArray(array('Update2',$upd2,$bio['user']->profile_image_url));
						
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
	
														Email::SendEmail($to,$subject,$message,$headers);
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
	
								API::_UpdateCache($r['twitterid'],$langs,$avg,array($spam[0],$spam[1]));
								
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
									$this->dbbind->UpdateSpamDetails($bio['user']->id,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
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
	#                                Email::SendEmail($to,$subject,$message,$headers);
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
    
	public function UpdateFakerQueue()
	{
		if ($_POST['ch'] == $this->cronhash)
        {
			$users = $this->dbbind->GetProcessors();
			
			//$this->errorschutney->DebugArray($users);
			
			foreach ($users as $u)
			{
				$this->dbbind->UpdateProcessorTime($u['twitterid'],time());
				
				$records = $this->dbbind->GetUserInQueue();
				
				//$this->errorschutney->DebugArray($records);
				
	            if (!empty($records))
				{
					
					foreach ($records as $r)
					{
						$spam = array();
						
						$details = $this->dbbind->GetTwitterDetails($u['twitterid']);
						
						$search = Validation::StripNonAlphanumeric($r['screen_name']);
				
						$bio = $this->twitterbind->GetUserByID($details[2],$details[3],$r['twitterid']);
						
						$countinfo = $this->dbbind->CountUserInfoRecords($r['twitterid']); 
                    
						//$this->errorschutney->PrintArray($countinfo);
					
                        if ($countinfo>=1)
                        {
							//$this->errorschutney->PrintArray($countinfo);
							$upd1 = $this->dbbind->UpdateUserInfo($r['twitterid'],$bio['user']->screen_name,$bio['user']->profile_image_url);
							
							//$this->errorschutney->PrintArray(array('Update1',$upd1,$bio['user']->profile_image_url));
						}
						
						//$this->errorschutney->PrintArray($bio);
						
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
	
														Email::SendEmail($to,$subject,$message,$headers);
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
											
										}
									}
								}
	
								$results['followers']=$followers;
								$results['checks']=$c;
								$results['potential']=$p;
								$results['spam']=$sc;
	
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
									$countSpamRecords = $this->dbbind->CountSpamRecords($bio['user']->id);
									
									$this->errorschutney->PrintArray($results);
									
									if ($countSpamRecords)
									{
										$this->dbbind->UpdateSpamDetails($bio['user']->id,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
										APIRequests::UpdateScore($bio['user']->id,$bio['user']->screen_name,$bio['user']->profile_image_url,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time(),1);
									}	
									else
									{
										$this->dbbind->AddSpamDetails($bio['user']->id,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time(),time());
										APIRequests::AddScore($bio['user']->id,$bio['user']->screen_name,$bio['user']->profile_image_url,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time(),1,1,time());	
									}
									
									$this->dbbind->UpdateUserQueue($bio['user']->id);
								}
								else
								{
									$this->dbbind->AddSpamError(print_r($bio,true),print_r($results,true),1,time());
								}
	
								$s = 0;
	
		//                        $this->errorschutney->PrintArray($spam);
	
	
							//}
					   
						}
						
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
	}
	
	protected function _MergeUsersAndChildren($users,$children)
	{
		if (!empty($users)&&!empty($children))
		{
			foreach ($children as $child)
			{
				if (!in_array($child['userid'],$users))
				{
					array_push($users,array('userid'=>$child['userid'],0=>$child['userid'],'FROM_UNIXTIME(sv.valid)'=>time(),1=>time()));
				}
			}
		}
		return $users;
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
                    
                    $fakes = $this->dbbind->GetFakes($u['userid'],1,50);
                    
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
			$emails = PaymentRequests::GetEmailList();
			
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
						
						Email::SendEmail($email,'StatusPeople Fakers Dashboard Subscription about to Expire',$message,$headers);
						
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
						
						Email::SendEmail($email,'StatusPeople Fakers Dashboard Subscription has Expired',$message,$headers);
						
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
						
						Email::SendEmail($email,'StatusPeople Fakers Dashboard Subscription Expiry Reminder',$message,$headers);
						
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
						
						Email::SendEmail($email,'Goodbye from StatusPeople Fakers Dashboard',$message,$headers);
						
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
		if ($_POST['ch'] == $this->cronhash)
		{
		
			$divers = DeepdiveRequests::GetDivers();
			
			$this->errorschutney->PrintArray($divers);
			
			foreach ($divers as $d)
			{
				$dive = DeepdiveRequests::GetTopDive($d['userid']);
				
				//$this->errorschutney->DebugArray($dive);
				
				$countids = DeepdiveRequests::CountFollowerIDs($dive[2]);
				
				if ($dive[5]!=0)
				{
					$details = $this->dbbind->GetTwitterDetails($dive[1]);
					
					$count = DeepdiveRequests::CountFollowerIDs($dive[2]);
					
					$cu = $dive[5];
					
					if ($count==0)
					{
						$idslist = $this->twitterbind->GetFollowerIDs($details[2],$details[3],$dive[2],$dive[5]);	
						
						if ($idslist['code']==200)
						{
							$success = true;
							$cu = $idslist['data']->next_cursor_str;
							$idlist = $idslist['data']->ids;
						}
						else
						{
							$success = false;
						}
					}
					else
					{
						if ($dive[4]<=50000)
						{
							$idslist = $this->twitterbind->GetFollowerIDs($details[2],$details[3],$dive[2],$dive[5]);	
							
							if ($idslist['code']==200)
							{
								$success = true;
								$cu = $idslist['data']->next_cursor_str;
								$idlist = $idslist['data']->ids;
							}
							else
							{
								$success = false;
							}
						}
						elseif ($dive[4]>50000&&$dive[4]<=150000)
						{
							$c = 0;
							
							while($c<2)
							{
								$idslist = $this->twitterbind->GetFollowerIDs($details[2],$details[3],$dive[2],$cu);
								
								//$this->errorschutney->PrintArray($cu);
								
								if ($idslist['code']==200)
								{
									$success = true;
								
									$cu = $idslist['data']->next_cursor_str;
									$idlist = $idslist['data']->ids;
									
									if ($cu==0)
									{
										$c=2;
									}
									
									$c++;
									
								}
								else
								{
									$success = false;
									
									$c = 2;
								}
							}
						}
						elseif ($dive[4]>150000&&$dive[4]<=300000)
						{
							$c = 0;
							
							while($c<3)
							{
								$idslist = $this->twitterbind->GetFollowerIDs($details[2],$details[3],$dive[2],$cu);
								
								//$this->errorschutney->PrintArray($cu);
								
								if ($idslist['code']==200)
								{
									$success = true;
									
									$cu = $idslist['data']->next_cursor_str;
									$idlist = $idslist['data']->ids;
									
									if ($cu==0)
									{
										$c=3;
									}
									
									$c++;
								}
								else
								{
									$c = 3;
									
									$success = false;
								}
							}
						}
						elseif($dive[4]>300000)
						{
							$c = 0;
							
							while($c<5)
							{
								$idslist = $this->twitterbind->GetFollowerIDs($details[2],$details[3],$dive[2],$cu);
								
								//$this->errorschutney->PrintArray($cu);
								
								if ($idslist['code']==200)
								{
									$success = true;
									
									$cu = $idslist['data']->next_cursor_str;
									$idlist = $idslist['data']->ids;
									
									if ($cu==0)
									{
										$c=5;
									}
									
									$c++;
								}
								else
								{
									$success = false;
									
									$c = 5;
								}
							}
						}
					}
					
					//$this->errorschutney->PrintArray($idslist);
					
					$ids = $idlist;
					//$cursor = $idslist['data']->next_cursor_str;
					$cursor = $cu;
					
					//$this->errorschutney->PrintArray($ids);
					$this->errorschutney->PrintArray(array('cursor'=>$cursor));
					
					if ($success)
					{
					
						$jsonids = json_encode($ids);
					
						DeepdiveRequests::AddFollowerIDs($dive[0],$dive[2],$jsonids,$cursor,time());
						
					}
					
					$countids = DeepdiveRequests::CountFollowerIDs($dive[2]);
					
					if ($countids==50)
					{
						DeepdiveRequests::UpdateCursor($dive[1],$dive[2],0);
					}
					else
					{
						DeepdiveRequests::UpdateCursor($dive[1],$dive[2],$cursor);	
					}
					
				}
			}
		}
	}
	
	public function GetDeepDiveFollowers()
	{
		if ($_POST['ch'] == $this->cronhash)
		{
		
			$divers = DeepdiveRequests::GetDivers();
			
			$this->errorschutney->PrintArray($divers);
			
			foreach ($divers as $d)
			{
				$dive = DeepdiveRequests::GetTopDive($d['userid']);
				
				//$this->errorschutney->DebugArray($dive);
				
				if ($dive[5]==0)
				{
				
					$countids = DeepdiveRequests::CountFollowerIDs($dive[2]);
					
					if ($countids>0)
					{
						//$this->errorschutney->PrintArray($dive);
					
						$followerids = DeepdiveRequests::GetFollowerIDs($dive[2]);
						
						if (!empty($followerids[3])&&$followerids[3]!=null&&$followerids[3]!='null')
						{
							//$this->errorschutney->DebugArray($followerids);
							
							$fidlist = json_decode($followerids[3]);
							
							$h = 0;
							$r = 0;
							$k = 0;
							
							foreach ($fidlist as $f)
							{
								if ($r == 5)
								{
									$newarrays[$k][]=$f;
									$r = 0;
									$h++;
								}
								else
								{
									$r++;
								}
								
								if ($h==100)
								{
									$h=0;
									$k++;
								}
							}
							
							$details = $this->dbbind->GetTwitterDetails($dive[1]);
							
							$fc = 0;
							
							$newcount = count($newarrays);
							
							//$this->errorschutney->PrintArray($k);
							$this->errorschutney->PrintArray($newcount);
							
							foreach ($newarrays as $array)
							{
								$count = count($array);
								
								//$this->errorschutney->DebugArray($followerids[0]['id']);
								
								$this->errorschutney->PrintArray($count);
								
								if ($count > 0 && $count<=100)
								{
									$followers = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$array,$count);
									
									if ($followers['code']==200)
									{
										$followers = json_encode($followers);
										
										//$this->errorschutney->PrintArray(strlen($followers));
										$addfollowers[$fc] = array($dive[2],$followerids[0],$followers,time());
										//DeepdiveRequests::AddFollowers($dive[2],$followerids[0],$followers,time());
										
										$fc++;
									}
									else
									{
										$this->errorschutney->PrintArray($followers);
									}
								}
							}
						}
						
						$this->errorschutney->PrintArray($fc);
						
						$nc = $newcount-2;
						
						if (($newcount-2)<1)
						{
							$nc = 1;
						}
						
						if ($fc>=$nc)
						{
							foreach ($addfollowers as $af)
							{
								//$this->errorschutney->DebugArray($af);
								
								DeepdiveRequests::AddFollowers($af[0],$af[1],$af[2],$af[3]);
							}
							
							DeepdiveRequests::UpdateFollowerIDsStatus($followerids[0]);
						}
							
						$checkedcount = DeepdiveRequests::CountFollowerIDs($dive[2]);
						
						if ($checkedcount == 0)
						{
							DeepdiveRequests::UpdateFinishedStatus($dive[0]);
						}
						
						unset($addfollowers);
						unset($fidlist);
						unset($newarrays);
					}
				}
			}
		}
	}
	
	public function GenerateDeepDiveScore()
	{
		if ($_POST['ch'] == $this->cronhash)
		{
		
			ini_set('memory_limit', '-1');
			
			$dives = DeepdiveRequests::GetFinishedDives();
			
			$this->errorschutney->PrintArray($dives);
			
			foreach ($dives as $d)
			{
				$followers = DeepdiveRequests::GetFollowers($d['twitterid']);
				
				//$this->errorschutney->DebugArray($followers[0]);
				
				$c = 0;
				$sc = 0;
				$p = 0;
				
				$api = new API();
				
				foreach ($followers as $fllwrs)
				{
					//$this->errorschutney->DebugArray($fllwrs);
					
					$fols = json_decode($fllwrs['followers']);
					
					if (!empty($fols->data))
					{
						foreach ($fols->data as $fl)
						{
							//$this->errorschutney->PrintArray($fl);	
							
							foreach ($fl as $k => $f)
							{
								$new[$k] = $f;
							}
							
							//$this->errorschutney->DebugArray($new);
							
							$faker = $api->_GetFakerStatus($new);
												
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
					
					//$this->errorschutney->PrintArray($fllwrs['id']);
					DeepdiveRequests::TurnOffFollowers($fllwrs['id']);
				}
				
				//$results['followers']=$followers;
				$results['checks']=$c;
				$results['potential']=$p;
				$results['spam']=$sc;
				$created = time();
				
				$this->errorschutney->PrintArray($results);
				
				$count = DeepdiveRequests::CountScores($d['twitterid']);
				
				if ($count==0)
				{
					DeepdiveRequests::AddScore($d['twitterid'],$results['spam'],$results['potential'],$results['checks'],$created);
				}
				else
				{
					DeepdiveRequests::UpdateScore($d['twitterid'],$results['spam'],$results['potential'],$results['checks'],$created);
				}
				
				//$this->errorschutney->PrintArray(memory_get_usage());
				//$this->errorschutney->PrintArray(memory_get_peak_usage());
				
				DeepdiveRequests::TurnOffDive($d['id']);
			}
		}
		//die('hello');
	}
	
	public function UpdateAPIScores()
	{
		if ($_POST['ch'] == $this->cronhash)
		{
		
			$scores = $this->dbbind->GetSpamScoreDetails();
			
			$time = time();
			//$this->errorschutney->DebugArray($scores);
			
			foreach ($scores as $s)
			{
				$type = 1; 
				
				//$this->errorschutney->DebugArray($s);
				
				if ($s['followers']>=10000)
				{
					$checkdeepdive = DeepdiveRequests::CountScores($s['twitterid']);
					
					if ($checkdeepdive>0)
					{
						$deepdivescore = DeepdiveRequests::GetScores($s['twitterid']);
						
						$s['checks'] = $deepdivescore[3];
						$s['spam'] = $deepdivescore[2];
						$s['potential'] = $deepdivescore[1];
						$s['updated'] = $deepdivescore[4];
						$type = 2;
					}
				}
					
				$check = APIRequests::CheckForScore($s['twitterid']);
				
				$good = $s['checks'] - ($s['spam']+$s['potential']);
				
				if ($check>0)
				{
					//$checkdate = APIRequests::CheckScoreAndDate($s['twitterid'],$s['updated']);
					
					if ($s['updated']>(time()-((3600*24)*5)))
					{
						APIRequests::UpdateScore($s['twitterid'],$s['screen_name'],$s['avatar'],$good,$s['potential'],$s['spam'],$s['checks'],$s['followers'],$type,$s['updated'],$time);
					}
				}
				else
				{
					//$this->errorschutney->PrintArray($s);
					//die('Ok!!');
					APIRequests::AddScore($s['twitterid'],$s['screen_name'],$s['avatar'],$good,$s['potential'],$s['spam'],$s['checks'],$s['followers'],$type,$s['updated'],$time);
				}
				
				$this->dbbind->UpdateAPICheck($s['twitterid'],$time);
				
				//$this->errorschutney->PrintArray($s);
			}
		}
	}
	
	public function AddKey()
	{
		$twitterid = 198192466;
		
		$hash = $this->validationchutney->HashString(time().$twitterid.rand(0,9999));
		
		APIRequests::AddKey($twitterid,$hash,time());
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
	
	public function AddEmails()
	{
		$emails = $this->paymentbind->GetAllUserDetails();
		
		//$this->errorschutney->PrintArray($emails);
		
		$time = time();
		
		foreach ($emails as $e)
		{
			
			$this->errorschutney->PrintArray($e);
			
			$this->dbbind->AddMarketingEmail($e['email'],$e['forename'],$e['surname'],$time);
			
			//die();
		}
	}
	
	/*public function AddMainEmails()
	{
		$emails = $this->mainbind->GetEmailAddresses();
		
		$time = time();
		
		$this->errorschutney->PrintArray(count($emails));
		
		$string = '';
		
		$c = 0;
		
		foreach ($emails as $e)
		{
			$string .= '("'.$e['email'].'","'.$e['forename'].'","'.$e['surname'].'",'.$time.'),';
			
 			if ($c == 500)
			{
				$string = substr($string,0,-1);
		
				//$this->errorschutney->DebugArray($string);
		
				$this->dbbind->AddMarketingEmails($string);		
				
				$string = '';
				$c = 0;
			}
			else
			{
				$c++;	
			} 
		}
		
		$string = substr($string,0,-1);
		
				//$this->errorschutney->DebugArray($string);
		
		$this->dbbind->AddMarketingEmails($string);
		
	} */
	
	public function SendMarketingEmail()
	{
		if ($_POST['ch'] == $this->cronhash)
		{
			//$emails = $this->dbbind->GetMarketingEmails();
			/*$emails = array(array('email'=>'rob@statuspeople.com','forename'=>'Rob'),
							array('email'=>'ben@statuspeople.com','forename'=>'Ben'),
							array('email'=>'rdwaller1984@googlemail.com','forename'=>'Rob'),
							array('email'=>'benj.christensen01@gmail.com','forename'=>'Ben'));*/
			
			/*$emails = array(array('email'=>'rob@statuspeople.com','forename'=>'Rob'),
							array('email'=>'rdwaller1984@googlemail.com','forename'=>'Rob'));*/
			
			$this->errorschutney->PrintArray(count($emails));
			
			$headers['from'] = 'StatusPeople <info@statuspeople.com>';
			$headers['reply'] = 'info@statuspeople.com';
			$headers['return'] = 'info@statuspeople.com';
			
			foreach ($emails as $e)
			{
				$message = $this->_MarketingMessage($e);
				
				$send = Email::SendEmail($e['email'],'Join our Social Media Training Webinars on Friday and Monday and Learn How to Boost Engagement and ROI From Twitter',$message,$headers,1);
				
				$this->errorschutney->PrintArray($e);
				
			}
		}
	}
	
	protected function _MarketingMessage($e)
	{
		$message = '';
		$message .= '<p>Hi '.$e['forename'].',</p>';
		$message .= '<p>This is just a short message to remind you that we have two Social Media training webinars coming up over the next few days.</p>';
		$message .=	'<p>Both will help you boost your ROI and Engagement from Twitter. Take a look and <a href="http://www.eventbrite.co.uk/o/statuspeoplecom-and-robdwaller-5712889239?s=20969151" style="color:#36b6d5;">buy some tickets today</a>...</p>';
		$message .= '<ul>';
		$message .= '<li><a href="http://www.eventbrite.co.uk/e/webinar-understand-twitter-follower-quality-analytics-to-boost-roi-tickets-10042795271" style="color:#36b6d5;">Understand Twitter Follower Quality & Analytics to Boost ROI</a><br/><small>(Fri 17th Jan 2014 15:00 GMT/10:00 EST) &pound;14.99/&#36;24.99</small></li>';
		$message .= '<li><a href="http://www.eventbrite.co.uk/e/webinar-how-influence-and-influencers-can-boost-engagement-and-roi-on-twitter-tickets-10077591347" style="color:#36b6d5;">How Influence and Influencers can Boost Engagement and ROI on Twitter</a><br/><small>(Mon 20th Jan 2014 19:00 GMT/14:00 EST) &pound;14.99/&#36;24.99</small></li>';
		$message .= '</ul>';
		$message .= '<p>And if you\'re interested in attending any of our other training webinars <a href="http://www.eventbrite.co.uk/o/statuspeoplecom-and-robdwaller-5712889239?s=20969151" style="color:#36b6d5;">take a look here...</a></p>';
		$message .= '<p>We hope to connect with you over the next few days.</p>';
		$message .= '<p>Thanks,</p>';
		$message .= '<p>StatusPeople</p>';
		
		//$this->errorschutney->DebugArray($message);
		
		return $message;
	}
	
	public function RateLimitTest()
	{
		
#                    $_SESSION['userid'] = 114873621;
#                  $_SESSION['userid'] = 31386162;
#					$_SESSION["userid"] = 633786383;
					$userid = 198192466;
#					$_SESSION['userid'] = 545309711;
#					$_SESSION['userid'] = 96269828;
#					$_SESSION['userid'] = 1101473544;
#					$_SESSION['userid'] = 1919216960;
#					$_SESSION['userid'] = 18746024;
#					$_SESSION['userid'] = 2147483647;
# 					$_SESSION['userid'] = 32816581;
#					$_SESSION['userid'] = 573776137;
		
		$details = $this->dbbind->GetTwitterDetails($userid);
		
		$result = $this->twitterbind->RateLimit($details[2],$details[3],'followers,users');
		
		$this->errorschutney->DebugArray($result);
		
	}
	
/* 	public function SportTest()
	{
		$page = $this->curlbind->	
	} */
}
?>