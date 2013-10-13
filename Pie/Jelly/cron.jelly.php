<?php

class Cron extends Jelly
{
    
    private $cronhash = '42c3fe3bb11e9014479a36f8faeff2469c8433178c99829f62f7f83d9d7d11eb';
    
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
        if ($_POST['ch'] == $this->cronhash)
        {
            $records = $this->dbbind->GetUsersToCheck(6);
            
//            $this->errorschutney->PrintArray($records);
            
            if (!empty($records))
            {
                foreach ($records as $r)
                {
					$this->dbbind->UpdateLastCheckTime($r['twitterid'],$r['screen_name'],time());
					
                    $spam = array();
                    
                    $details = $this->dbbind->GetTwitterDetails($r['userid']);
                    
                    $search = $this->validationchutney->StripNonAlphanumeric($r['screen_name']);
            
                    $bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);

//                    $this->errorschutney->PrintArray($bio);

//                    $uid = $bio['user']->id;

//                    $spamrecords = $this->dbbind->GetSpamDetails($uid);

/*                     $followers = $bio['user']->followers_count; 

                    $requests = round($followers/5000);

                    if ($requests > 10)
                    {
                        $requests = 10;
                    }
                    elseif ($requests < 1)
                    {
                        $requests = 1;
                    }

                    $a = 0;
                    $c = 1;
                    $cursor = '-1';

                    $breach = false;
                    
                    while ($c <= $requests&&!$breach)
                    {
                        $idslist = $this->twitterbind->GetFollowerIDsByName($details[2],$details[3],$search,$cursor);

                        if ($idslist['code']==429)
                        {
                            $breach = true;
                        }
                    
                        $fids[$a] = $idslist['data'];
                        
                        $cursor = $fids[$a]->next_cursor_str;

                        $a++;
                        $c++;
                    }

                    $h = 0; 
                    $i = 0;

    //                $this->errorschutney->PrintArray($fids);

                    if (!$breach)
                    {
                        foreach ($fids as $ids)
                        {
        //                    $this->errorschutney->DebugArray($ids);

                            if (!isset($ids->errors)&&!isset($ids->error)&&!empty($ids->ids))
                            {

                                foreach ($ids->ids as $id)
                                {
                                    $hndrds[$h][] = $id; 

                                    if ($i == 99)
                                    {
                                        $h++;
                                        $i = 0;
                                    }
                                    else
                                    {
                                        $i++;
                                    }
                                }

                            }
                        }

                        $h++;

        //                echo $h.'<br/>';

        //                $this->errorschutney->PrintArray($hndrds); */
					
						$gethundreds = API::_GetHundreds($search,$bio,$details,10);
						$hndrds = $gethundreds[0];
						$h = $gethundreds[1];
						$followers = $gethundreds[2];

                        if (!empty($hndrds))
                        {
/*                             if ($h < 5)
                            {
                                $checks = $h;
                            }
                            elseif ($h >= 5 && $h < 100)
                            {
                                $checks = 5;
                            }
                            elseif ($h >= 100 && $h < 300)
                            {
                                $checks = 7;
                            }
                            elseif ($h >= 300)
                            {
                                $checks = 10;
                            }

                            $incr0 = round($h/$checks,1);
                            $incr = round($h/$checks,0,PHP_ROUND_HALF_DOWN);

                            if ($incr0 <= 1.6)
                            {
                                $incr = 1;
                            }
                            
//                            if ($incr < 2)
//                            {
//                                $incr = 1;
//                            }

                            //echo $incr.'<br/>';

                            $y = 0;
                            $z = 1;

        //                    echo $h;
        //                    echo $checks;

                            while ($z <= $checks)
                            {
                                if(count($hndrds[round($y*$incr)])<100&&$z==$checks&&$followers>500)
            					{
        							$chcks[$y] = round(($y*$incr)-1);
        						}
        						else
        						{
        							$chcks[$y] = round($y*$incr);  
        						}

                                $y++;
                                $z++;
                            }

        //                    $this->errorschutney->PrintArray($chcks); */
							
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

            //                                    $this->errorschutney->PrintArray($follower);

/*                                             $ffratio = 0;

                                            if ($follower['friends']>0)
                                            {
                                                $ffratio = round(($follower['followers']/$follower['friends'])*100); 
                                            }

                                            if ($ffratio < 20)
                                            {
                                                if ($follower['tweets']==0||$follower['followers']==0)
                                                {
                                                    $sc++;
            //                                        $this->errorschutney->PrintArray($follower);
                                                    $spam[] = $follower;
                                                }
                                                elseif($ffratio<=2)
                                                {
                                                    $sc++;
            //                                        $this->errorschutney->PrintArray($follower);
                                                    $spam[] = $follower;
                                                }
                                                elseif($ffratio<10&&empty($follower['website'])&&$follower['favourites']==0)
                                                {
                                                    $sc++;
            //                                        $this->errorschutney->PrintArray($follower);
                                                    $spam[] = $follower;
                                                }
                                                else 
                                                {
                                                    $p++;
                                                }

                                            }
                                            elseif($follower['followers'] < 20&&$follower['friends']<20&&$follower['tweets']<20)
                                            {
                                                $p++;
                                            }
                                            $c++; */
											
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

    //                        $this->errorschutney->PrintArray($results);

    //                        $rb = ($checks*100)-100;
    //                        $rt = $checks*100;

							$this->_UpdateCache($r['userid'],$langs,$avg,array($spam[0],$spam[1]));
							
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
                                    if ($s < 20)
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

                        }
                   
                    }
                    
                    unset($spam);
                    unset($chcks);
                    unset($hndrds);
                    unset($fids);
                    unset($results);
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
            
            if ($users)
            {
                
                foreach ($users as $u)
                {
                    $details = $this->dbbind->GetTwitterDetails($u['userid']);
                    
					//$this->errorschutney->PrintArray($details);
                    
                    $fakes = $this->dbbind->GetFakes($u['userid'],15);
                    
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
                    
                    $this->dbbind->UpdateAutoRemove($u['userid'],$u['userid'],time());
                }
                
            }
            
         } 
    }
    
	public function SendSubscriptionReminder()
	{
/* 		if ($_POST['ch'] == $this->cronhash)
        { */
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
			
/* 		} */
	}
	
	public function GetStatusPeopleSubscriberDetails()
	{
		$details = $this->paymentbind->GetSubscriberDetails();
		
		$this->errorschutney->PrintArray($details);
	}

	public function TestEmailSend()
	{
		$email = 'test@test.com';
		
		$this->dbbind->AddEmailSend($email,'Goodbye from StatusPeople Fakers Dashboard',time());
	}
	
}
?>