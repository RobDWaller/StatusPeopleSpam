<?php

class API extends Jelly
{
    
    # Header #
    
    private $ResponseFormat;
	private $Salt1;
	private $Salt2;
	
	
    # End Header #
    
    function __construct() {
        parent::__construct();
        
        $httporigin = $_SERVER['HTTP_ORIGIN'];
        $allowed = array('http://tools.statuspeople.com','http://test.statuspeople.com');
        
        if (in_array($httporigin,$allowed))
        {        
            header('Access-Control-Allow-Origin:'.$httporigin);
        }
		
		$this->Salt1 = SALT_ONE;
		$this->Salt2 = SALT_TWO;
		
		ini_set('max_execution_time', 300);
    }
    
    # Public Functions #
    
    # Twitter #
    
    public function GetTwitterBio($vars)
    {
        
        $this->ResponseFormat = $vars['rf'];
        $twid = $this->validationchutney->UnobscureNumber(urldecode($vars['twid']),$this->Salt1);
        
//        $this->ResponseFormat = 'json';
//        $twid = '31386162';
        
        $this->_CheckForResponseFormat();
        
        if ($twid)
        {
			//$this->errorschutney->PrintArray($twid);
			
            $details = $this->dbbind->GetTwitterDetails($twid);
            
			//$this->errorschutney->DebugArray($details);
            
            $bio = $this->twitterbind->GetUserByID($details[2],$details[3],$twid);
            
			//$this->errorschutney->DebugArray($bio);
            
            $bio = $this->twitterchutney->ProcessTwitterBio($bio);
            
            //$this->_APISuccess(201,'Request Successful',$bio);
            
            if (empty($bio))
            {
                $this->_APIFail(400,'No Data returned for this Bio');
            }
            else 
            {
                $this->_APISuccess(201,'Request Successful',$bio);
            }
        }
        else
        {
            $this->_APIFail(400,'No user defined');
        }  
    }
    
    
    public function GetSpamScores($vars)
	{
		
        $this->ResponseFormat = $vars['rf'];
		$user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
		//$user = $vars['usr'];
        $search = $vars['srch'];
		$searches = $vars['srchs'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$search)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
            
			//$this->errorschutney->PrintArray($details);
            
            $search = $this->validationchutney->StripNonAlphanumeric($search);
            
            $bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);
            
			//$this->errorschutney->DebugArray($bio);
            
            $uid = $bio['user']->id;
            
			$countsearch = 1;
			
			if ($user==$uid)
			{
				$countsearch = 0;
			}
			
			//$this->errorschutney->DebugArray($countsearch);
			
            $spamrecords = $this->dbbind->GetSpamDetails($uid);
            
			$true = true;
            
			$Days1 = strtotime('-1 Day');
            
			if ($spamrecords[7]<$Days1)
			//if ($true)
			{   
				$gethundreds = $this->_GetHundreds($search,$bio,$details,7);
				$hndrds = $gethundreds[0];
				$h = $gethundreds[1];
				$followers = $gethundreds[2];
                
                if (!empty($hndrds))
                {

					$chcks = $this->_GetChecks($h,$hndrds,$followers);

                    $c = 0;
                    $sc = 0;
                    $p = 0;
                    
                    foreach ($chcks as $ch)
                    {
                        if (!empty($hndrds[$ch]))
                        {
                            $followerdetails = $this->_GetFollowerDetails($details,$hndrds,$ch);
                            
                            if ($followerdetails['code'] == 200)
                            {
                                
                                foreach ($followerdetails['data'] as $follower)
                                {	
									$faker = $this->_GetFakerStatus($follower);
									
									//$this->errorschutney->PrintArray($faker);
									
									if ($faker['status']==1)
									{
										$sc++;
										$spam[] = $faker['follower'];
									}
									elseif ($faker['status']==2)
									{
										$p++;
										//$this->errorschutney->PrintArray($faker);
									}
									
                                    $c++;
                                }
								
								$langs = $this->_GetLanguageDetails($followerdetails['data'],$langs);
								
								$avg = $this->_GetAverages($followerdetails['data'],$avg);
							
                            }
                        }
                    }
                    
					//$this->errorschutney->DebugArray($avg);
					
					$langs = $this->_ReorderLanguages($langs);
					//$this->errorschutney->PrintArray($langs);
					//$this->errorschutney->PrintArray($avg);
					
                    $results['followers']=$followers;
                    $results['checks']=$c;
                    $results['potential']=$p;
                    $results['spam']=$sc;
                    $results['lang'] = $langs[0];
					$results['hundred'] = $avg['hundred'];
					$results['fr250'] = $avg['fr250'];
					$results['spam1'] = $spam[0];
					$results['spam2'] = $spam[1];
//                    $this->errorschutney->DebugArray($results);
                    
					$this->_UpdateCache($uid,$langs,$avg,array($spam[0],$spam[1]));
					
                    if (isset($spamrecords[7])&&$spamrecords[7]>0)
                    {
                        $this->dbbind->UpdateSpamDetails($uid,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
                    
                        $countinfo = $this->dbbind->CountUserInfoRecords($uid); 
                    
                        if ($countinfo==0)
                        {
                            $this->dbbind->AddUserInfo($uid,$bio['user']->screen_name,$bio['user']->profile_image_url,time(),time());
                        }
                        
                    }
                    else 
                    {
                        $this->dbbind->AddSpamDetails($uid,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time(),time());
                    
                        $countinfo = $this->dbbind->CountUserInfoRecords($uid); 
                    
                        if ($countinfo==0)
                        {
                            $this->dbbind->AddUserInfo($uid,$bio['user']->screen_name,$bio['user']->profile_image_url,time(),time());
                        }
                        
                    }
					
					if (!empty($spam))
					{
						foreach ($spam as $spm)
						{
							if ($s < 5)
							{
								$insertstring .= '('.$bio['user']->id.','.$spm['id'].',"'.$spm['screen_name'].'","'.$spm['image'].'",'.time().'),';
							}
		
							$s++;
						}
						
						$is = substr($insertstring,0,-1);
	
	//                        $this->errorschutney->PrintArray($is);
	
						$this->dbbind->AddFakes($is);
					}
					
                   
                }
                else 
                {
                    $this->_APIFail(500,'No User Data Found.');
                }
            }
            else 
            {
				//die();
                $spamscores = $this->dbbind->GetSpamDetails($uid);
                $cache = $this->_GetCache($uid);
				
				//$this->errorschutney->DebugArray($cache);
				
                $results['followers']=$spamscores[5];
                $results['checks']=$spamscores[4];
                $results['potential']=$spamscores[3];
                $results['spam']=$spamscores[2];
				
				$results['lang'] = $cache['lang'];
				$results['hundred'] = $cache['hundred'];
				$results['fr250'] = $cache['fr250'];
				$results['spam1'] = $cache['spam1'];
				$results['spam2'] = $cache['spam2'];
				
            }
            
			
			
            if (empty($results))
            {
                $this->_APIFail(500,'No User Data Returned.');
            }
            else 
            {
				//$searches = $_COOKIE['searches'];
				$results['searches'] = $searches;
				
				if ($countsearch>0)
				{
					//$this->errorschutney->PrintArray($_COOKIE);
					//$this->errorschutney->PrintArray($countsearch);
				
					$newsearches = 0;
					
					//$this->errorschutney->PrintArray($searches);
					
					if ($searches>0)
					{
						$newsearches = $searches - 1;
						//$this->errorschutney->PrintArray($newsearches);
					}
					
					$this->dbbind->UpdateSearches($user,$newsearches);
					//setcookie('searches',$newsearches,time()+3600000);
					//$this->errorschutney->PrintArray($newsearches);
					$results['searches'] = $newsearches;
				}
				
                $this->_APISuccess(201, 'Request Successful, Twitter User Data Found.',$results);
            }
        }
        else 
        {
            $this->_APIFail(400,'No user defined');
        }
    }
    
	public function GetUpdateFakersList($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        $search = $vars['srch'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$search)
        {
            $spam = array();
                    
            $details = $this->dbbind->GetTwitterDetails($user);

            $search = $this->validationchutney->StripNonAlphanumeric($search);

            $bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);

//                    $this->errorschutney->PrintArray($bio);

//                    $uid = $bio['user']->id;

//                    $spamrecords = $this->dbbind->GetSpamDetails($uid);
			$gethundreds = $this->_GetHundreds($search,$bio,$details,7);
			$hndrds = $gethundreds[0];
			$h = $gethundreds[1];
			$followers = $gethundreds[2];
			
			/*             $followers = $bio['user']->followers_count;

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

            while ($c <= $requests)
            {
                $idslist = $this->twitterbind->GetFollowerIDsByName($details[2],$details[3],$search,$cursor);
                    
                if ($idslist['code']==429)
                {
                    $this->_APIFail(429,'Twitter API 1.1 limit breached. Please wait 15 minutes and try again.');
                }

                $fids[$a] = $idslist['data'];

                $cursor = $fids[$a]->next_cursor_str;

                $a++;
                $c++;
            }

            $h = 0; 
            $i = 0;

//                $this->errorschutney->PrintArray($fids);

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

            $h++;*/

//                echo $h.'<br/>';

//                $this->errorschutney->PrintArray($hndrds);

            if (!empty($hndrds))
            {
		
				$chcks = $this->_GetChecks($h,$hndrds,$followers);
    
				$c = 0;
                $sc = 0;
                $p = 0;
				
/*                 if ($h < 5)
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

                $incr = round($h/$checks,0,PHP_ROUND_HALF_DOWN);

                if ($incr < 2)
                {
                    $incr = 1;
                }

                //echo $incr.'<br/>';

                $y = 0;
                $z = 1;

//                    echo $h;
//                    echo $checks;

                while ($z <= $checks)
                {
                    $chcks[$y] = round($y*$incr);  

                    $y++;
                    $z++;
                }

//                    $this->errorschutney->PrintArray($chcks);

                $c = 0;
                $sc = 0;
                $p = 0; */

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

//                                    echo $fcnt;

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

                                $ffratio = 0;

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
                                    else 
                                    {
                                        $p++;
                                    }

                                }
                                elseif($follower['followers'] < 20&&$follower['friends']<20&&$follower['tweets']<20)
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

//                        $this->errorschutney->PrintArray($results);

                if ($results['followers']>500)
                {
                    $cks = $checks*100;
                }
                else
                {
                    $cks = $results['followers'];
                }

/*                 if ($results['checks']==$cks)
                {
                    $this->dbbind->AddCheckScore($bio['user']->id,$bio['user']->screen_name,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
                    $update = $this->dbbind->UpdateUsersToCheckTime($r['twitterid'],$r['screen_name'],time());
                } */

                $s = 0;

				//$this->errorschutney->PrintArray($spam);

                $insertstring = '';

                foreach ($spam as $spm)
                {
                    if ($s < 200)
                    {
                        $insertstring .= '('.$bio['user']->id.','.$spm['id'].',"'.$spm['screen_name'].'","'.$spm['image'].'",'.time().'),';
                    }

                    $s++;
                }

                $is = substr($insertstring,0,-1);

				//$this->errorschutney->DebugArray($is);

                $this->dbbind->AddFakes($is);

            }

            if (empty($spam))
            {
                $this->_APIFail(500,'No Spam data found at this time.');
            }
            else
            {
                $this->_APISuccess(201,'List updated successfully.','');
            }

        }
        else
        {
            $this->_APIFail(400,'No user data submitted.');
        }
        
    }
	
    public function GetFakersScoreUpdate($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $vars['usr'];
        $search = $vars['srch'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$search)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
            
            //$this->errorschutney->PrintArray($details);
            
            $search = $this->validationchutney->StripNonAlphanumeric($search);
            
            $bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);
            
            //$this->errorschutney->PrintArray($bio);
            
            $uid = $bio['user']->id;
            
            $spamrecords = $this->dbbind->GetSpamDetails($uid);
            
            $true = true;
            
            $Days1 = strtotime('-1 Day');
            
//            if ($spamrecords[7]<$Days1)
//            {    
            if ($true)
            {
            
				$gethundreds = $this->_GetHundreds($search,$bio,$details,7);
				$hndrds = $gethundreds[0];
				$h = $gethundreds[1];
				$followers = $gethundreds[2];
				
                if (!empty($hndrds))
                {

					$chcks = $this->_GetChecks($h,$hndrds,$followers);
					$c = 0;
                    $sc = 0;
                    $p = 0;
//                    $this->errorschutney->PrintArray($chcks);

                    foreach ($chcks as $ch)
                    {
                        $followerdetails = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$hndrds[$ch],100);

                        if ($followerdetails!=false)
                        {

                            foreach ($followerdetails as $follower)
                            {
								
								$faker = $this->_GetFakerStatus($follower);
									
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
							
							$langs = $this->_GetLanguageDetails($followerdetails['data'],$langs);
								
							$avg = $this->_GetAverages($followerdetails['data'],$avg);
                        }
                    }
                    
                    $results['followers']=$followers;
                    $results['checks']=$c;
                    $results['potential']=$p;
                    $results['spam']=$sc;
                    
//                    $this->errorschutney->DebugArray($results);
                    $langs = $this->_ReorderLanguages($langs);
					
					$this->_UpdateCache($uid,$langs,$avg,array($spam[0],$spam[1]));
					
                    $this->dbbind->UpdateSpamDetails($uid,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
                    
                    $countinfo = $this->dbbind->CountUserInfoRecords($uid); 

                    if ($countinfo==0)
                    {
                        $this->dbbind->AddUserInfo($uid,$bio['user']->screen_name,$bio['user']->profile_image_url,time(),time());
                    }
                   
                }
                else 
                {
                    $this->_APIFail(500,'No User Data Found.');
                }
            }
            else 
            {
                $spamscores = $this->dbbind->GetSpamDetails($uid);
                
                $results['followers']=$spamscores[5];
                $results['checks']=$spamscores[4];
                $results['potential']=$spamscores[3];
                $results['spam']=$spamscores[2];
            }
            
            if (empty($results))
            {
                $this->_APIFail(500,'No User Data Returned.');
            }
            else 
            {
                $this->_APISuccess(201, 'Request Successful, Twitter User Data Found.',$results);
            }
        }
        else 
        {
            $this->_APIFail(400,'No user defined');
        }
    }
    
    public function GetSpamRecords($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $vars['usr'];
        $search = $vars['srch'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$search)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
                    
            $search = $this->validationchutney->StripNonAlphanumeric($search);

            $bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);
			
			$gethundreds = $this->_GetHundreds($search,$bio,$details,10);
			$hndrds = $gethundreds[0];
			$h = $gethundreds[1];
			$followers = $gethundreds[2];
				
            if (!empty($hndrds))
            {

				$chcks = $this->_GetChecks($h,$hndrds,$followers);
                $c = 0;
                $sc = 0;
                $p = 0;

                foreach ($chcks as $ch)
                {
/*                     if (!empty($hndrds[$ch]))
                    { */
                        $followerdetails = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$hndrds[$ch],100);

                        if ($followerdetails!=false)
                        {

                            foreach ($followerdetails['data'] as $follower)
                            {
								
								$faker = $this->_GetFakerStatus($follower);
									
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
								//echo $c;
                            }
							
							$langs = $this->_GetLanguageDetails($followerdetails['data'],$langs);
								
							$avg = $this->_GetAverages($followerdetails['data'],$avg);
                        }
/*                     } */
                }

                $results['followers']=$followers;
                $results['checks']=$c;
                $results['potential']=$p;
                $results['spam']=$sc;
				
				$langs = $this->_ReorderLanguages($langs);
				
				$this->_UpdateCache($uid,$langs,$avg,array($spam[0],$spam[1]));
//                        $this->errorschutney->PrintArray($results);
//                        $this->errorschutney->DebugArray($spam);

                $rb = ($checks*100)-100;
                $rt = $checks*100;

                if ($results['checks']<=$rt&&$results['checks']>=$rb)
                {
                    $this->dbbind->AddCheckScore($bio['user']->id,$bio['user']->screen_name,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
                }

                $s = 0;

//                        $this->errorschutney->PrintArray($spam);
				if (!empty($spam))
				{
					foreach ($spam as $spm)
					{
						if ($s < 200)
						{
							$insertstring .= '('.$bio['user']->id.','.$spm['id'].',"'.$spm['screen_name'].'","'.$spm['image'].'",'.time().'),';
						}
	
						$s++;
					}
				}
				
                $is = substr($insertstring,0,-1);

//                        $this->errorschutney->PrintArray($is);

                $this->dbbind->AddFakes($is);

                $update = $this->dbbind->UpdateUsersToCheckTime($r['twitterid'],$r['screen_name'],time());
                
                if (empty($results))
                {
                    $this->_APIFail(500,'No User Data Returned.');
                }
                else 
                {
                    $this->_APISuccess(201, 'Request Successful, Twitter User Data Found.',$results);
                }
                
            }
            else
            {
                $this->_APIFail(500,'Request Failed, No User Data Returned.');
            }
        }
        else 
        {
            $this->_APIFail(400,'No user defined');
        }
    }
		
	public function GetCacheData($vars)
	{
		$this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt2);
        
		$this->_CheckForResponseFormat();
        
        if ($user)
        {
			$cache = $this->_GetAllCache($user);
			
			if (!empty($cache))
			{
				 $this->_APISuccess(201, 'Request Successful, Cache Data Found.',$cache);
			}
			else
			{
				$this->_APIFail(500,'Cache data could not be found');
			}
		}
        else 
        {
            $this->_APIFail(400,'No user defined');
        }
	}
	
	public function _GetFakerStatus($follower)
	{
		
			$ffratio = 0;
			$status = 0;
	
		//$this->errorschutney->PrintArray($follower);
		
			if ($follower['friends']>0&&$follower['followers']>0)
			{
				$ffratio = round(($follower['followers']/$follower['friends'])*100); 
			}
			elseif($follower['friends']==0&&$follower['followers']>50)
			{
				$ffratio = 21;
			}
		
			if ($ffratio < 20)
			{
				if ($follower['tweets']==0||$follower['followers']==0)
				{
					$status = 1;
					//$this->errorschutney->PrintArray($follower);
				}
				elseif($ffratio<=2)
				{
					$status = 1;
					//$this->errorschutney->PrintArray($follower);
				}
				elseif($ffratio<10&&empty($follower['website'])&&$follower['favourites']==0)
				{
					$status = 1;
					//$this->errorschutney->PrintArray($follower);
				}
				/*elseif($this->_CheckLanguageQuality($follower))
				{
					$status = 1;
				}*/ 
				else 
				{
					$status = 2;
				}
				
			}
			elseif($follower['followers'] < 20&&$follower['friends']<20&&$follower['tweets']<20)
			{
				$status = 2;
			}
			elseif($follower['tweetsperday']<=0.1||$follower['lasttweet']>=90)
			{
				$status = 2;
			}
		
			$result['status'] = $status;
			$result['follower'] = $follower;
		
/* 			if (!$status)
			{
				$this->errorschutney->PrintArray($follower);
			} */
			
			return $result;
		
	}
    
	public function _CheckLanguageQuality($text)
	{
		//$query = '/\s{2,}||(.,)/';
		$result = false;
		
		$count = 0;
		
		$query = '/\.,|!,|,!|\.!|!-|\s_\w|\s\/\w|\*\.|\s,\s|\s;\s|\s:\s|\w,\w|\w\*\w|\w\|\w|\w!\w|\s{2,}|\.\||\.\?|\s\?\w|\w\?\w|\s@\s|\.@|\.~|\w#\w|\w~\w|,,|\.\*|\.-|\._|\s\'\w|\s!\w|\.;|;amp;|\sd\s|\se\s|\sf\s|\sg\s|\sh\s|\sj\sl\sm\s|\sn\s|\ss\s|\sv\s|\sw\s|\sz\s|\sporn\s|\sanal\s|\ssex\s|\sxxx\s/';
			
		if ($text['language']=='en')
		{
			if (!empty($text['description']))
			{
				preg_match_all($query,$text['description'],$matches1);
				
				$count += count($matches1[0]);
				
				if ($count<=2)
				{
					preg_match_all($query,$text['tweet'],$matches2);
					
					$count += count($matches2[0]);
					
					if ($count>=2&&$count<4)
					{
						if ($text['lasttweet']>=10)
						{
							$result = true;
						}
					}
					elseif ($count>=4)
					{
						$result = true;
					}
				}
				else
				{
					$result = true;
				}
			}
			else
			{
				preg_match_all($query,$text['tweet'],$matches3);
					
				$count += count($matches3[0]);
				
				if ($count>=2&&$count<4)
				{
					if ($text['lasttweet']>=10)
					{
						$result = true;
					}
				}
				elseif($count>=4)
				{
					$result = true;
				}
			}
		}
		
		//$this->errorschutney->PrintArray($count);
		
/* 		if($result)
		{
			$this->errorschutney->PrintArray($matches1);
			$this->errorschutney->PrintArray($matches2);
			$this->errorschutney->PrintArray($matches3);
			
			$this->errorschutney->PrintArray($text);
		} */
		
		return $result;
	}
	
	public function _GetLanguageDetails($followers,$langs)
	{
		$languages = $this->validationchutney->LanguageList();
		
		//$this->errorschutney->DebugArray($languages);
		
		foreach ($followers as $fl)
		{
			foreach ($languages as $k => $l)
			{
				if ($k==substr($fl['language'],0,2))
				{
					$langs[$k]['name'] = $l->name;
					$langs[$k]['count'] = $langs[$k]['count'] += 1; 
				}
			}
		}
		
		return $langs;
	}
	
	public function _ReorderLanguages($langs)
	{
		if (!function_exists('reorder'))
		{
			function reorder($a,$b)
			{
				if ($a['count'] == $b['count']) {
					return 0;
				}
				return ($a['count'] < $b['count']) ? 1 : -1;
			}
		}
		
		if (!empty($langs))
		{
			usort($langs,'reorder');
		}
		
		return $langs;
	}
	
	public function _GetAverages($followers,$averages)
	{
		
		foreach ($followers as $fl)
		{
			$averages['tweets_pd'] += $fl['tweetsperday'];
			$averages['followers'] += $fl['followers'];
			//$this->errorschutney->PrintArray($fl['screen_name']);
			//$this->errorschutney->PrintArray($averages['followers']);
			//$this->errorschutney->PrintArray($fl['followers']);
			
			$averages['friends'] += $fl['friends'];
			if ($fl['lasttweet']>1&&$fl['lasttweet']<30)
			{
				$averages['one'] += 1;
			}
			elseif ($fl['lasttweet']>=30&&$fl['lasttweet']<100)
			{
				$averages['thirty'] += 1;
			}
			elseif ($fl['lasttweet']>=100)
			{
				$averages['hundred'] += 1;
			}
			
			if ($fl['friends']<250)
			{
				$averages['fr250'] += 1;
			}
			elseif ($fl['friends']>=250&&$fl['friends']<1000)
			{
				$averages['fr500'] += 1;
			}
			elseif ($fl['friends']>=1000)
			{
				$averages['fr1000'] += 1;
			}
			
			if ($fl['followers']<250)
			{
				$averages['fo250'] += 1;
			}
			elseif ($fl['followers']>=250&&$fl['followers']<1000)
			{
				$averages['fo500'] += 1;
			}
			elseif ($fl['followers']>=1000)
			{
				$averages['fo1000'] += 1;
			}
			
			$averages['tweets_pd'] += $fl['tweetsperday'];
			$averages['count'] += 1;
		}
		
		return $averages;
	}
	
	protected function _GetFollowerDetails($details,$hndrds,$ch)
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
					if ($fcnt == 2)
					{
						$this->_APIFail(500,'Twitter are having data process problems currently. Please try again later.');
					}
				}
				
				//                                    echo $fcnt;
				
				$fcnt++;
			}
			else
			{
				$fllwrs = true;
			}
			
		}
		
		return $followerdetails;
		
	}
	
	public function _GetHundreds($search,$bio,$details,$reqs)
	{
		//$this->errorschutney->PrintArray($details);
		
		$followers = $bio['user']->followers_count; 

		$requests = round($followers/5000);
		
		if ($requests > $reqs)
		{
			$requests = $reqs;
		}
		elseif ($requests < 1)
		{
			$requests = 1;
		}
		
		$a = 0;
		$c = 1;
		$cursor = '-1';
		
		while ($c <= $requests)
		{
			$idslist = $this->twitterbind->GetFollowerIDsByName($details[2],$details[3],$search,$cursor);
			
			//                    $this->errorschutney->PrintArray($idslist['code']);
			//                    $this->errorschutney->PrintArray($idslist['data']);
			
			if ($idslist['code']==429)
			{
				//$this->errorschutney->PrintArray($details);
				$result = $this->twitterbind->RateLimit($details[2],$details[3],'followers,users');
				self::_APIFail(429,'Twitter API 1.1 limit breached. Please wait 15 minutes and try again.',$result->resources);
			}
			
			$fids[$a] = $idslist['data'];
			
			$cursor = $fids[$a]->next_cursor_str;
			
			$a++;
			$c++;
		}
		
		$h = 0; 
		$i = 0;
		
		//                $this->errorschutney->PrintArray($fids);
		
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
		
		return array($hndrds,$h,$followers);
	}
	
	public function _GetChecks($h,$hndrds,$followers)
	{
		if ($h < 5)
		{
			$checks = $h;
		}
		elseif ($h >= 5 && $h < 100)
		{
			$checks = 5;
		}
		elseif ($h >= 100)
		{
			$checks = 7;
		}
		elseif ($h >= 300)
		{
			$checks = 10;
		}
		
		$incr = round($h/$checks,0,PHP_ROUND_HALF_DOWN);
		
		if ($incr < 2)
		{
			$incr = 1;
		}
		
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
		
		return $chcks;
	}
	
    public function GetSpamScoresOverTime($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt2);
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $scores = $this->dbbind->GetScoresOverTime($user,20);
            
//            $this->errorschutney->DebugArray($scores);
            
            if ($scores)
            {
                $i = 0;
                $k = 0;
                $d = 0;
                
                while ($d<20)
                {
                    if (!$d)
                    {
                        $dates[] = date('M d',time());
                    }
                    else
                    {
                        $dates[] = date('M d',strtotime('-'.$d.' days'));
                    }
                    
                    $d++;
                }
                
#                $this->errorschutney->PrintArray($dates);
                
                foreach ($dates as $dt)
                {
                    
                    $fake = round(($scores[$k]['spam']/$scores[$k]['checks'])*100);
                    $inactive = round(($scores[$k]['potential']/$scores[$k]['checks'])*100);
                    $good = 100-($fake+$inactive);
                    $created = $scores[$k]['created'];
                    
                    if ($dt == $scores[$k]['date'])
                    {
                        $data['Fake'][$i] = array('count'=>$fake,'date'=>$scores[$k]['date']);
                        $data['Inactive'][$i] = array('count'=>$inactive,'date'=>$scores[$k]['date']);
                        $data['Good'][$i] = array('count'=>$good,'date'=>$scores[$k]['date']);
                    }
                    else
                    {
                        $data['Fake'][$i] = array('count'=>$fake,'date'=>$dt);
                        $data['Inactive'][$i] = array('count'=>$inactive,'date'=>$dt);
                        $data['Good'][$i] = array('count'=>$good,'date'=>$dt);
                    }
                    
                    if (is_array($scores[$k+1]))
                    {
                        //$this->errorschutney->PrintArray($scores[$k+1]);
                        $k++;
                    }
                    
                    $i++;
                }
                
                $this->_APISuccess(201, 'Data returned successfully.',$data);
            }
            else
            {
                $this->_APIFail(500,'No data was returned.');
            }
        }
        else
        {
            $this->_APIFail(400,'No user details submitted.');
        }
    }
    
    public function GetCachedSpamScore($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $scores = $this->dbbind->GetScoresOverTime($user,1);
            
//            $this->errorschutney->DebugArray($scores);
            
            if ($scores)
            {
                $i = 0;
                
                foreach ($scores as $s)
                {
                    $fake = round(($s['spam']/$s['checks'])*100);
                    $inactive = round(($s['potential']/$s['checks'])*100);
                    $good = 100-($fake+$inactive);
                    $created = $s['created'];
                    
                    $data['Fake'][$i] = array('count'=>$fake,'date'=>$s['date']);
                    $data['Inactive'][$i] = array('count'=>$inactive,'date'=>$s['date']);
                    $data['Good'][$i] = array('count'=>$good,'date'=>$s['date']);
                    
                    $i++;
                }
                
                $this->_APISuccess(201, 'Data returned successfully.',$data);
            }
            else
            {
                $this->_APIFail(500,'No data was returned.');
            }
        }
        else
        {
            $this->_APIFail(400,'No user details submitted.');
        }
    }
    
	public function PostBlockedSearch()
	{
		$this->ResponseFormat = $_POST['rf'];
        $user = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
        $search = $_POST['srch'];
		
        $this->_CheckForResponseFormat();
        
        if ($user&&$search)
        {
			$fakes = $this->dbbind->FindFake($user,$search);
			
			if (!empty($fakes))
			{
				$this->_APISuccess(201, 'Fake Data returned successfully.',$fakes);
			}
			else
			{
				$this->_APIFail(500,'No fake data was returned.');
			}
		}
		else
		{
			$this->_APIFail(400,'No user details submitted.');
		}
	}
	
	public function GetUserDetailsCount($vars)
	{
		$this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
			$count = $this->paymentbind->CountUserDetails($user);
			
			if ($count)
			{
				$this->_APISuccess(201,'User Exists',$count);
			}
			else
			{
				$this->_APIFail(500,'User does not exist.');
			}
		}
		else
		{
			$this->_APIFail(400,'No user details submitted.');
		}
	}
	
	public function PostAddUserDetails()
	{
		$this->ResponseFormat = $_POST['rf'];
        $user = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
		$email = $_POST['em'];
		$title = $_POST['tt'];
		$fname = $_POST['fn'];
		$lname = $_POST['ln'];
        
        $this->_CheckForResponseFormat();
		
		$valid[] = $this->validationchutney->ValidateEmail($email);
		$valid[] = $this->validationchutney->ValidateString($title,'Title'); 
		$valid[] = $this->validationchutney->ValidateString($fname,'First Name'); 
		$valid[] = $this->validationchutney->ValidateString($lname,'Last Name'); 
		
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
			$count = $this->paymentbind->CountUserDetails($user);
			
			if ($count)
			{
				$searches = $this->dbbind->GetSearches($user);
				$add = $searches[0]+5;
				$this->dbbind->UpdateSearches($user,$add);
				$data['searches'] = $add;
				
				$this->_APISuccess(201, 'User already exists.',$data);
			}
			else
			{
				$adddetails = $this->paymentbind->AddUserDetails($user,$email,$title,$fname,$lname,time());
				
				if ($adddetails>0)
                {
                    $searches = $this->dbbind->GetSearches($user);
					$add = $searches[0]+5;
					$this->dbbind->UpdateSearches($user,$add);
					$data['searches'] = $add;
					
					$headers['from'] = 'StatusPeople <fakers@statuspeople.com>';
					$headers['reply'] = 'fakers@statuspeople.com';
					$headers['return'] = 'fakers@statuspeople.com';
					
					$message = '<p>Dear '.$fname.',</p>';
					$message .= '<p>Thanks for connecting with StatusPeople Fakers App. To help you get more insight on the Fakers universe we have given you 5 free extra friend searches. Cool huh?</p>';
					$message .= '<p>If you want unlimited friend searches <a href="http://fakers.statuspeople.com/Payments/Subscriptions" style="color:#36b6d5;">Sign up for a subscription</a> and get access to all our other dashboard tools.</p>';
					$message .= '<p style="text-align:center;"><a href="https://statuspeoplestatuspeople01.s3.amazonaws.com/8dea3263d6d60ab01e5bec28aaad8aa9d5dc3dbdf780cef278dd0237cacf1a36"><img src="https://statuspeoplestatuspeople01.s3.amazonaws.com/8dea3263d6d60ab01e5bec28aaad8aa9d5dc3dbdf780cef278dd0237cacf1a36" height="250px" width="185px" border="0px" align="center"/></a></p>';
					$message .= '<p>And if you have any questions just drop us a line at info@statuspeople.com or <a href="http://twitter.com/StatusPeople" style="color:#36b6d5;">@StatusPeople</a>.</p>';
					$message .= '<p>Cheers, StatusPeople</p>';
					
					$this->emailchutney->SendEmail($email,'Welcome to StatusPeople Fakers App',$message,$headers);
					
					$this->_APISuccess(201, 'User added successfully.',$data);
				}
                else
				{
					$this->_APIFail(500,'Failed to create user.');
				}
			}
		}
		else
		{
			$this->_APIFail(400,'Details submitted were not valid.',$message);
		}
	}
	
    public function GetCompetitorCount($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $competitors = $this->dbbind->GetCompetitorCount($user);
			$valid = $this->paymentbind->GetValidDate($user);
            
            if ($competitors>=0)
            {
                $this->_APISuccess(201, 'Data returned successfully.',array('competitors'=>$competitors,'type'=>$valid[1]));
            }
            else
            {
                $this->_APIFail(500,'No data was returned.');
            }
        }
        else
        {
            $this->_APIFail(400,'No user details submitted.');
        }
    }
    
    public function GetCompetitorList($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $competitors = $this->dbbind->GetCompetitors($user);
            
            if (empty($competitors))
            {
                $this->_APIFail(500,'No data was returned.');
            }
            else
            {
				foreach ($competitors as $k => $cmp)
				{
					$competitors[$k]['userid'] = $this->validationchutney->ObscureNumber($cmp['userid'],$this->Salt2);
					$competitors[$k][0] = $this->validationchutney->ObscureNumber($cmp['userid'],$this->Salt2);
					$competitors[$k]['twitterid'] = $this->validationchutney->ObscureNumber($cmp['twitterid'],$this->Salt2);
					$competitors[$k][1] = $this->validationchutney->ObscureNumber($cmp['twitterid'],$this->Salt2);
				}
				
                $this->_APISuccess(201, 'Data returned successfully.',$competitors);
            }
        }
        else
        {
            $this->_APIFail(400,'No user details submitted.');
        }
    }
    
    public function GetTwitterUserData($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        $search = $vars['srch'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$search)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
		
            $user = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);
            
//            $this->errorschutney->DebugArray($user);
            
            if ($user['code'] == 200)
            {
                $userdata = $this->twitterchutney->ProcessUserDetails($user['user']);
                
//                $this->errorschutney->DebugArray($userdata);
                
                if (empty($userdata))
                {
                    $this->_APIFail(500,'No User Data Returned.');
                }
                else {
                    $this->_APISuccess(201, 'Request Successful, Twitter User Data Found.', $userdata);
                }
            }
            else
            {
                $this->_APIFail(500,'Internal Server Error. Could not get data from Twitter.');
            }
        }
        else 
        {
            $this->_APIFail(400,'No user data received.');
        }
    }
    
    public function GetFollowerData($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        $count = $vars['ct'];
        $name = $vars['nm'];
        
        $this->_CheckForResponseFormat();
        
        if ($count&&$name)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
            
            $followers = $this->twitterbind->GetFollowersList($details[2],$details[3],$name,$count);
            
//            $this->errorschutney->PrintArray($followers);
            
            if ($followers)
            {
                $data = $this->twitterchutney->ProcessUsersDetails($followers);
                
//                $this->errorschutney->DebugArray($data);
                
                $this->_APISuccess(201,'Request Successful',$data);
            }
            else
            {
                $this->_APIFail(500,'Internal Server Error, no data retrieved');
            }
        }
        else
        {
            $this->_APIFail(400,'Account does not exist');
        }  
            
    }
    
    public function GetUserTwitterTimeline($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        $search = $vars['srch'];
        $count = $vars['cnt'];
        
        $this->_CheckForResponseFormat();
        $this->_CheckInteger($count, 'Count');
        
        if ($user&&$search)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
            
            $tweets = $this->twitterbind->GetTwitterTimelineByUser($details[2],$details[3],$search,$count);
            
//            $this->errorschutney->DebugArray($tweets);
            
            if (empty($tweets))
            {
                $this->_APIFail(500,'Internal Server Error, no data retrieved');
            }
            else
            {
//                $timeline = $this->twitterchutney->ProcessTimelineData($tweets,'Europe/London');
                $this->_APISuccess(201,'Request Successful',$tweets);
            }
                
        }
        else 
        {
            $this->_APIFail(400,'No user data received.');
        }
    }
    
    public function GetKredScore($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $vars['usr'];
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $kred = $this->kredbind->GetKredScore($user);
            
//            $this->errorschutney->DebugArray($kred);
            
            if (empty($kred->data))
            {
                $this->_APIFail(500,'Kred Score Not Found.');
            }
            else
            {
                $data['influence'] = 0;
                $data['outreach'] = 0;
                
                if ($kred->data[0]->influence)
                {
                    $data['influence'] = $kred->data[0]->influence;
                }
                
                if ($kred->data[0]->outreach)
                {
                    $data['outreach'] = $kred->data[0]->outreach;
                }
                
                $this->_APISuccess(201, 'Request Success, Kred Score Found',$data);
            }
        }
        else
        {
            $this->_APIFail(400,'No user data received.');
        } 
        
    }
    
    public function GetSpamList($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $fakes = $this->dbbind->GetFakes($user,1,5);
            
            if (empty($fakes))
            {
                $this->_APIFail(500,'No data was returned.');
            }
            else
            {
                $this->_APISuccess(201, 'Data returned successfully.',$fakes);
            }
        }
        else
        {
            $this->_APIFail(400,'No user details submitted.');
        }
    }
	
	public function GetBlockedList($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $this->validationchutney->UnobscureNumber(urldecode($vars['usr']),$this->Salt1);
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $fakes = $this->dbbind->GetFakes($user,0,5);
            
            if (empty($fakes))
            {
                $this->_APIFail(500,'No data was returned.');
            }
            else
            {
                $this->_APISuccess(201, 'Data returned successfully.',$fakes);
            }
        }
        else
        {
            $this->_APIFail(400,'No user details submitted.');
        }
    }
    
    public function PostTweet()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
        $tweet = urldecode($_POST['txt']);
        
        $this->_CheckForResponseFormat();
        $this->_CheckLongText($tweet,'Tweet');
        
        if ($tweet)
        {
            //$this->_APISuccess(201, 'Tweet Sent Successfully.', $result);
            
            $details = $this->dbbind->GetTwitterDetails($user);

            $tweet = $this->sttsplbind->ConvertToShortURL(0, $tweet, 0, 0);

            if (strlen($tweet['text']) <= 140)
            {
                    $success = $this->twitterbind->SendTweet($details[2],$details[3],$tweet['text'],$replyid);

                    //$this->errorschutney->DebugArray($success);

                    if ($success['success'])
                    {
                            $this->_APISuccess(201, 'Tweet Sent Successfully.', $result);
                    }
                    else
                    {
                            $this->_APIFail(500,'There was an error connecting to Twitter, please wait a moment and send again.');
                    }

            }
            else
            {
                $this->_APIFail(400, 'Tweet was greater than 140 characters, please shorten it and try again.');
            }
        }
        else
        {
            $this->_APIFail(400,'No tweet submitted.');
        }
    }
    
    public function PostAddFaker()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
        $search = $_POST['srch'];
        $spam = $_POST['sp'];
        $potential = $_POST['pt'];
        $checks = $_POST['ch'];
        $followers = $_POST['fl'];
        $accounttype = 1;
        
        if (isset($_POST['typ']))
        {
            $accounttype = $_POST['typ'];
        }
        
        $this->_CheckForResponseFormat();
        $this->_CheckInteger($spam, 'Spam score');
        $this->_CheckInteger($potential, 'Potential score');
        $this->_CheckInteger($checks, 'Checks score');
        $this->_CheckInteger($followers, 'Followers score');
        
        if ($user&&$search)
        {
			$valid = $this->paymentbind->GetValidDate($user);
			
			$allowed = 6;
			
			if ($valid[1]==2)
			{
				$allowed = 16;
			}
			
			$count = $this->dbbind->GetCompetitorCount($user);
			
			if ($count<$allowed)
			{
				$details = $this->dbbind->GetTwitterDetails($user);
	
				$search = $this->validationchutney->StripNonAlphanumeric($search);
				
				$bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);
				
				if ($bio)
				{
					$check = $this->dbbind->CheckForFakerCheck($user,$bio['user']->id);
					
					if (!$check)
					{
					
						$add = $this->dbbind->AddFakerCheck($user,$bio['user']->id,$search,$bio['user']->profile_image_url,$accounttype,time(),time());
	
						if ($add)
						{
							$addscore = $this->dbbind->AddFakerCheckScore($bio['user']->id,$search,$spam,$potential,$checks,$followers,time());
							
							if ($addscore)
							{
								$this->_APISuccess(201, 'User successfully added to fakers list.','');
							}
							else
							{
								$this->_APISuccess(201, 'User added to fakers list.','');
							}
							
						}
						else
						{
							$this->_APIFail(500,'Failed to add user to fakers list.');
						}
					
					}
					else
					{
						$this->_APIFail(400,'User already on Fakers List.');
					}
				}
				else
				{
					$this->_APIFail(400,'User could not be found on Twitter.');
				}
			}
			else
			{
				$this->_APIFail(400,'Max number of trackable friends reached.');
			}	
        }
        else 
        {
            $this->_APIFail(400,'No data submitted.');
        }
    }

    public function PostDeleteFaker()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
        $twid = $this->validationchutney->UnobscureNumber($_POST['twid'],$this->Salt2);
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$twid)
        {
            if ($user!=$twid)
			{
				$delete = $this->dbbind->DeleteFakerCheck($user,$twid);
				
				if ($delete)
				{
					$this->_APISuccess(201, 'User successfully removed from fakers list.','');
				}
				else
				{
					$this->_APIFail(500,'Failed to remove user from fakers list.');
				}
			}   
			else
			{
				$this->_APIFail(500,'You cannot delete your primary account.');
			}
        }
        else
        {
            $this->_APIFail(400,'Failed to delete user. No user data submitted.');
        }
    }

    public function PostBlockSpam()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
        $twid = $_POST['twid'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$twid)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
            
            $destroy = $this->twitterbind->DestroyFriendship($details[2],$details[3],$twid);
            
			//$this->errorschutney->DebugArray($destroy);
            
            if ($destroy)
            {
                $block = $this->dbbind->BlockSpam($user,$twid);
				$blocks = $this->dbbind->CountBlocked($user);
                
                if ($block)
                {    
                    $this->_APISuccess(201, 'User successfully blocked.',$blocks);
                }
                else 
                {
                    $this->_APISuccess(201, 'User blocked.',$destroy);
                }
            }
            else
            {
                $this->_APIFail(500,'Failed to block twitter user.');
            }
        }
        else
        {
            $this->_APIFail(400,'No data submitted.');
        }
    }
	
	public function PostUnBlockSpam()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
        $twid = $_POST['twid'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$twid)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
            
            $unblock = $this->twitterbind->Unblock($details[2],$details[3],$twid);
            
//            $this->errorschutney->DebugArray($destroy);
            
            if ($unblock['code']==200)
            {
                $this->dbbind->NotSpam($user,$twid);
                
                $this->_APISuccess(201, 'User successfully unblocked.',$destroy);
                
            }
            else
            {
                $this->_APIFail(500,'Failed to block twitter user.');
            }
        }
        else
        {
            $this->_APIFail(400,'No data submitted.');
        }
    }
    
    public function PostNotSpam()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
        $twid = $_POST['twid'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$twid)
        {
            $block = $this->dbbind->NotSpam($user,$twid);
                
            if ($block)
            {    
                $this->_APISuccess(201, 'User marked as not spam.','');
            }
            else 
            {
                $this->_APIFail(500,'Failed to mark user as not spam.');
            }
            
        }
        else
        {
            $this->_APIFail(400,'No data submitted.');
        }
    }
    
	public function GetTrendCheck()
	{
		$userid = 198192466;
		
		$details = $this->dbbind->GetTwitterDetails($userid);
		
		//$q = '@presidenciamx';
		//$q = '#SaluteNaurozBaloch';
		//$q = '#BanGeo';
		//$q = '#xmasjumperday';
		//$q = '#ExtinctionDay';
		$q = '#MeReEnojoCuando';
		
		$search = $this->twitterbind->SearchTweets($details[2],$details[3],$q,100,'mixed','');
		
		
		//$this->errorschutney->DebugArray($search);
		
		$sc = 0;
		$p = 0;
		$c = 0;
		$rt = 0;
		
		$retweets = array();
		
		foreach ($search['data']->statuses as $d)
		{
			//$this->errorschutney->PrintArray($d->user);
			//$this->errorschutney->PrintArray($d);
			
/* 			if ($c==5)
			{
				die();
			} */
			
/* 			if ($d->retweeted)
			{
				$rt++;
			}
			
			$user = $this->twitterchutney->ProcessSpamUser($d->user);
			$this->errorschutney->PrintArray(array('id'=>$d->id_str,'tweet'=>$d->text,'user'=>$user));
			
			$faker = $this->_GetFakerStatus($user);
									
			//$this->errorschutney->PrintArray($faker);
			
			if ($faker['status']==1)
			{
				$sc++;
				$spam[] = $faker['follower'];
			}
			elseif ($faker['status']==2)
			{
				$p++;
				//$this->errorschutney->PrintArray($faker);
			}
			
			$c++; */
		
# 			$ids[$c] = $d->user->id_str;
			
			
			if ($d->retweet_count>5)
			{
				//$this->errorschutney->PrintArray($d->retweeted_status->id_str);
				
				if (!in_array($d->retweeted_status->id_str,$retweets))
				{
					$retweets[$c] = $d->retweeted_status->id_str;
					$tweets[$c] = $d->retweeted_status;
					$c++; 
				}
			}
			
		}
		
		function RetweetSort($a,$b)
		{
			$a1 = $a->retweet_count;
			$b1 = $b->retweet_count;
			
			if ($a1==$b1)
			{
				return 0;
			}
			
			return ($a1>$b1) ? -1 : 1 ;
		}
		
		usort($tweets,'RetweetSort');
		
		//$this->errorschutney->DebugArray($tweets);
		
		$c1 = 0;
		
		foreach ($tweets as $t)
		{
			if ($c1<5)
			{
				$result = $this->twitterbind->GetRetweetDataById($details[2],$details[3],$t->id_str);
				
				//$this->errorschutney->DebugArray($result);
				
				if ($result['code']==200)
				{
					$data[$c1]['id'] = $t->id_str;
					$data[$c1]['tweet'] = $t->text;
					$data[$c1]['retweets'] = $this->_BuildRetweets($result);
				}
				else
				{
					$this->errorschutney->PrintArray($t->id_str);
					$this->errorschutney->PrintArray($t->text);
					$this->errorschutney->PrintArray($result);
				}
			}
			
			$c1++;
		}
		
		//$this->errorschutney->DebugArray($data);
		
		foreach ($data as $dt)
		{
			
			$this->errorschutney->PrintArray($dt['tweet']);
			
			$count = count($dt['retweets']);
			$start = $dt['retweets'][$count-1]['retweeted_at'];
			
			$c2 = 0;
			$s = 0;
			$n = 0;	
			$v = 0;
			
			foreach ($dt['retweets'] as $r)
			{
				$check = $this->_AssessRetweet($r,$start);
			
				if ($check['status'])
				{
					$clean[$n] = $check['record'];
					$n++;
				}
				else
				{
					$spam[$s] = $check['record'];
					$s++;
				}
				
				if ($check['velocity'])
				{
					$v++;
				}
				
				$c2++;
			}
			
			$this->errorschutney->DebugArray(array('count'=>$c2,'velocity'=>$v,'spam'=>$s,'spam_records'=>$spam,'clean'=>$n,'clean_records'=>$clean));
			
		}
		
		//$this->errorschutney->PrintArray($retweets);
		//$this->errorschutney->PrintArray($tweets);
		
		//$ids = substr($ids,0,-1);
		
/* 		$this->errorschutney->PrintArray($ids);
		
		$count = count($ids);
		
		$followerdetails = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$ids,$count);
		
		$this->errorschutney->PrintArray($followerdetails);
		
		$sc = 0;
		$p = 0;
		$c = 0;
		
		foreach ($followerdetails['data'] as $u)
		{
			$faker = $this->_GetFakerStatus($u);
									
			//$this->errorschutney->PrintArray($faker);
			
			if ($faker['status']==1)
			{
				$sc++;
				$spam[] = $faker['follower'];
			}
			elseif ($faker['status']==2)
			{
				$p++;
				//$this->errorschutney->PrintArray($faker);
			}
			
			$c++;
		}
		
		$this->errorschutney->PrintArray(array('count'=>$c,'spam'=>$sc,'potential'=>$p)); */
	}
	
	protected function _BuildRetweets($retweets)
	{
		$c = 0;
		
		$time = time();
		
		foreach ($retweets['data'] as $r)
		{
			$array[$c]['screen_name'] = $r->user->screen_name;
			$array[$c]['description'] = $r->user->description;
			$array[$c]['url'] = $r->user->url;
			$array[$c]['retweeted_at'] = strtotime($r->created_at);
			$array[$c]['source'] = $r->source;
			$array[$c]['user_created'] = strtotime($r->user->created_at);
			$array[$c]['user_created_date'] = date('Y/m/d',strtotime($r->user->created_at));
			$array[$c]['lang'] = $r->user->lang;			
			$array[$c]['friends'] = $r->user->friends_count;
			$array[$c]['followers'] = $r->user->followers_count;
			$array[$c]['tweets'] = $r->user->statuses_count;
			$array[$c]['favourites'] = $r->user->favourites_count;
			$array[$c]['listed'] = $r->user->listed_count;
			if ($r->user->friends_count==0)
			{
				$array[$c]['follower_friend'] = 0;
			}
			else
			{
				$array[$c]['follower_friend'] = round($r->user->followers_count/$r->user->friends_count,2);	
			}
			$array[$c]['days'] = round((($time-$array[$c]['user_created'])/3600)/24);
			$array[$c]['tweets_per_day'] = round($r->user->statuses_count/(round((($time-$array[$c]['user_created'])/3600)/24)),2);
			//$array[$c]['followers_tweets'] = round($r->user->followers_count/$r->user->statuses_count,2);
			
			$c++;
		}
		
		return $array;
	}
	
	protected function _AssessRetweet($retweet,$start)
	{
		$f = 0;
		$status = 1;
		$velocity = 0;
		
		if ($retweet['source']=='web')
		{
			$f++;	
			$f++;
		}
		if ($retweet['followers']<=50&&$retweet['friends']<=50)
		{
			$f++;
		}
		if ($retweet['follower_friend']<=0.2)
		{
			$f++;
		}
		if ($retweet['tweets_per_day']<=0.5&&$retweet['tweets']>50)
		{
			$f++;
		}
		if (empty($retweet['description'])&&empty($retweet['url']))
		{
			$f++;
		}
		
		if ($f>=4)
		{
			$status = 0;
		}
		
		if (($retweet['retweeted_at']-$start)<=180)
		{
			$velocity = 1;
		}
		
		return array('status'=>$status,'record'=>$retweet,'velocity'=>$velocity);
		
	}
	
	public function ViewRetweets()
	{
		$userid = 1919216960;
		
		$details = $this->dbbind->GetTwitterDetails($userid);
		
		//$result = $this->twitterbind->GetRetweetData($details[2],$details[3],100);
		$result = $this->twitterbind->GetRetweetDataById($details[2],$details[3],'410398079129370625');
		
		//$this->errorschutney->DebugArray($result);
		
		$c = 0;
		
		$time = time();
		
		$count = count($result['data']);
		
		//$this->errorschutney->PrintArray($count);
		
		$start = strtotime($result['data'][$count-1]->created_at);
		$endm1 = $result['data'][1]->created_at;
		$end = $result['data'][0]->created_at;
		
		//$this->errorschutney->DebugArray(array($start,$endm1,$end));
		
		foreach ($result['data'] as $r)
		{
			$array[$c]['screen_name'] = $r->user->screen_name;
			$array[$c]['description'] = $r->user->description;
			$array[$c]['url'] = $r->user->url;
			$array[$c]['retweeted_at'] = strtotime($r->created_at);
			$array[$c]['source'] = $r->source;
			$array[$c]['user_created'] = strtotime($r->user->created_at);
			$array[$c]['user_created_date'] = date('Y/m/d',strtotime($r->user->created_at));
			$array[$c]['lang'] = $r->user->lang;			
			$array[$c]['friends'] = $r->user->friends_count;
			$array[$c]['followers'] = $r->user->followers_count;
			$array[$c]['tweets'] = $r->user->statuses_count;
			$array[$c]['favourites'] = $r->user->favourites_count;
			$array[$c]['listed'] = $r->user->listed_count;
			$array[$c]['follower_friend'] = round($r->user->followers_count/$r->user->friends_count,2);
			//$array[$c]['tweets_per_day'] = round($r->user->statuses_count/($time-$array[$c]['user_created']),2);
			$array[$c]['tweets_per_day'] = round($r->user->statuses_count/(round((($time-$array[$c]['user_created'])/3600)/24)),2);
			$array[$c]['followers_tweets'] = round($r->user->followers_count/$r->user->statuses_count,2);
			
			
			
			$c++;
		}
		
		//$this->errorschutney->PrintArray($array);
		
		$s = 0;
		$n = 0;
		$c1 = 0;
		$v = 0;
		
		foreach ($array as $a)
		{
/* 			if ($a['source']=='web')
			{
				if ($a['followers']<=50&&$a['friends']<=50)
				{
					$spam[$s] = $a;
					$s++;
				}
				elseif ($a['follower_friend']<=0.2)
				{
					$spam[$s] = $a;
					$s++;
				}
				elseif ($a['tweets_per_day']<=0.5)
				{
					$spam[$s] = $a;
					$s++;
				}
				else
				{
					$clean[$n] = $a;
					$n++;
				}
			}
			else
			{
				$clean[$n] = $a;
				$n++;
			}
			
			$c1++; */
			
			$check = $this->_AssessRetweet($a,$start);
			
			if ($check['status'])
			{
				$clean[$n] = $check['record'];
				$n++;
			}
			else
			{
				$spam[$s] = $check['record'];
				$s++;
			}
			
			if ($check['velocity'])
			{
				$v++;
			}
			
			$c1++;
		}
		
		$this->errorschutney->DebugArray(array('count'=>$c1,'velocity'=>$v,'spam'=>$s,'spam_records'=>$spam,'clean'=>$n,'clean_records'=>$clean));
	}
	
    # End Twitter #
	
	public function PostAddDive()
	{
		//$userid = 1919216960;
		$userid = 198192466;
		//$userid = 545309711;
		//$userid = 31386162;
		//$userid = 633786383;
		//$userid = 96269828;
		//$userid = 1101473544;
		//$userid = 18746024;
		
		$user = 'number10gov';
		
		$details = $this->dbbind->GetTwitterDetails($userid);
		
		$bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$user);
		
		$this->errorschutney->PrintArray($bio['user']->id_str);
		$this->errorschutney->PrintArray($bio['user']->screen_name);
		$this->errorschutney->PrintArray($bio['user']->followers_count);
		
		$this->deepdivebind->AddDive($userid,$bio['user']->id_str,$bio['user']->screen_name,$bio['user']->followers_count,time());
	}
	
	public function PostAddSite()
	{
		$url = $_POST['url'];
		$this->ResponseFormat = $_POST['rf'];
		$this->_CheckForResponseFormat();
		
		$valid = $this->validationchutney->ValidateUrl($url);
		
		if ($valid[0])
		{
			$title = '';
			
			$sitedetails = $this->_GetUrlInfo($url);
			
			if (!empty($sitedetails['title']))
			{
				$title = $sitedetails['title'];
			}
			
			$ipaddress =  $_SERVER["REMOTE_ADDR"];
			$oneday = strtotime('-1 Day');
			
			$checkip = $this->dbbind->CheckIPCount($ipaddress,$oneday);
			
			if ($checkip<=10)
			{
				$check = $this->dbbind->CheckForSite($url);
				
				if ($check>0)
				{
					$site = $this->dbbind->GetSite($url);
					
					$count = $site[3] + 1;
					
					$update = $this->dbbind->UpdateSiteCount($site[0],$ipaddress,$count);
					
					if ($update>0)
					{
						$this->_APISuccess(201,'Thanks, your site has been added successfully, we will process it shortly.',$url);
					}
					else
					{
						$this->_APIFail(500,'We were unable to update this website at this time. Please try again in a bit.');
					}
				}
				else
				{
					$add = $this->dbbind->AddSite($url,$title,$ipaddress,time());
						
					if ($add>0)
					{
						$this->_APISuccess(201,'Thanks, your site has been added successfully.',$url);
					}
					else
					{
						$this->_APIFail(500,'We were unable to add this website at this time. Please try again later.');
					}
				}
			}
			else
			{
				$this->_APIFail(400,'Thanks for your suggestions, we\'re just processing them now. Why don\'t you come back in a day or so and submit some more.');
			}
		}
		else
		{
			$this->_APIFail(400,$valid[1]);
		}
	}
    
	public function GetUrlDetails($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $url = urldecode($vars['url']);
		
		
        $this->_CheckForResponseFormat();
        $this->_CheckURL($url);
        
        $urldetails = $this->_GetUrlInfo($url);
            
        $this->_APISuccess(201,'URL Data returned successfully', $urldetails);
    }
	
	public function PostChangeAutoRemoveStatus()
	{
		$this->ResponseFormat = $_POST['rf'];
		$twid = $this->validationchutney->UnobscureNumber($_POST['usr'],$this->Salt1);
        
        $this->_CheckForResponseFormat();
		
		if ($twid)
		{
			$count = $this->dbbind->CheckForFakerCheck($twid,$twid);	
			
			if ($count)
			{
				$getstatus = $this->dbbind->GetAutoRemoveStatus($twid);
				
				$autoremove = 1;
				
				if ($getstatus[0])
				{
					$autoremove = 0;
				}
				
				$update = $this->dbbind->UpdateAutoRemoveStatus($twid,$autoremove);
				
				if ($update)
				{
					$this->_APISuccess(201, 'Auto remove status updated.',$autoremove);
				}
				else
				{
					$this->_APIFail(500,'Updating auto remove status failed.');
				}
				
			}
			else
			{
				$this->_APIFail(500,'User could not be found.');
			}
		}
		else
		{
			$this->_APIFail(400,'No data submitted.');
		}
		
	}
	
    # End Public Function #
    
    
    
    # Protected Functions #
    
    protected function _CompleteFail()
    {
        header('Content-type: application/rss+xml');
        
        $output['xml'] = $this->xmlchutney->XMLAPIError(400,'#FAIL, No Response Format!!');

        $this->glaze->view('API/xml.php',$output);
        
        die();
    }
    
    protected function _APIFail($code,$message,$data=0)
    {
        
        if ($this->ResponseFormat == 'json')
        {
            $output['json'] = $this->jsonchutney->JSONAPIError($code,$message,$data);

            $this->glaze->view('API/json.php',$output);
        }
        elseif ($this->ResponseFormat == 'xml')
        {
            header('Content-type: application/rss+xml');

            $output['xml'] = $this->xmlchutney->XMLAPIError($code,$message,$data);

            $this->glaze->view('API/xml.php',$output);
        }
        
        die();
    }
    
    protected function _APISuccess($code,$message,$data,$simple = true,$tag = '')
    {
        
        if ($this->ResponseFormat == 'json')
        {
            $output['json'] = $this->jsonchutney->JSONAPIOutput(201,$message,$data);

            $this->glaze->view('API/json.php',$output);
        }
        elseif ($this->ResponseFormat == 'xml')
        {
            header('Content-type: application/rss+xml');

            $output['xml'] = $this->xmlchutney->XMLAPIOutput(201,$message,$data,$simple,$tag);

            $this->glaze->view('API/xml.php',$output);
        }
        
        die();
    }
    
    protected function _CheckForResponseFormat()
    {
        if ($this->ResponseFormat != 'json' && $this->ResponseFormat != 'xml')
        {
            $this->_CompleteFail();
        }
    }
    
    protected function _CheckLinkType($type)
    {
        
        if (!preg_match('/^[0-6]{1}$/', $type))
        {
            $this->_APIFail(400,'Link type does not exist',$this->ResponseFormat);
        }
        
    }
    
    protected function _CheckURL($url)
    {
         
        $valid = $this->validationchutney->ValidateUrl($url);
        
        if ($valid[0] == false)
        {
            $this->_APIFail(400,'URL not valid format',$this->ResponseFormat);
        }
     
    }
    
    protected function _CheckNetwork($network)
    {
        if (!empty($network))
        {
            
            if ($network < 1 || $network > 3)
            {
                $this->_APIFail(400,'Not a valid network.',$this->ResponseFormat);
            }
        }
    }
    
    protected function _CheckInteger($int,$name)
    {
        $valid = $this->validationchutney->ValidateInteger($int,$name);

        if ($valid[0] == false)
        {
            $this->_APIFail(400,'Not a valid '.$name.'.',$this->ResponseFormat);
        }
    }

    protected function _CheckText($text,$name)
    {
        if (!empty($text))
        {
            $valid = $this->validationchutney->ValidateString($text,$name);

            if ($valid[0] == false)
            {
                $this->_APIFail(400,'Not a valid '.$name.'.',$this->ResponseFormat);
            }
        }
    }
    
    protected function _CheckLongText($text,$name)
    {
        if (!empty($text))
        {
            $valid = $this->validationchutney->ValidateLongString($text,$name);

            if ($valid[0] == false)
            {
                $this->_APIFail(400,'Not a valid '.$name.'.',$this->ResponseFormat);
            }
        }
    }
    
    protected function _GetUrlInfo($url)
    {
            $contents = @file_get_contents($url);

            if ($contents)
            {
                //Looks for content within title tags
                preg_match('/<title>(.*)<\/title>/',$contents,$title);

                //If the title result is empty it looks again but across multiple lines.
                if (empty($title))
                {
                        preg_match('/<title>(.*)<\/title>/s',$contents,$title);
                }

                //Looks for the first paragraph of text.
                preg_match('/<p.*>(.*)<\/p>/',$contents,$firstpara);

                //Looks for an image
                $imgdetails = $this->_FindImage($contents);

                return array('title'=>html_entity_decode(trim($title[1]),ENT_QUOTES),'para'=>$firstpara[1],'image'=>$imgdetails);
            }
            else 
            {
                $this->_APIFail(400, 'URL Data Not Found');
            }
    }

    protected function _FindImage($contents)
    {

            //Looks for all images within the contents.
            preg_match_all('/<img.*\ssrc=\"([^\"]*)\"\s.*\/>|<img.*\ssrc=\"([^\"]*)\"\s.*>/',$contents,$images);

            $imagesrc = "";

            //Loops through every image found.
            foreach ($images[1] as $image)
            {
                    //Checks that the image source is a valid url.
                    $valid = $this->validationchutney->ValidateUrl($image);

                    if ($valid[0])
                    {
                            //Looks for image attributes.
                            list($width,$height,$type,$attr) = @getimagesize($image);

                            //Checks that image is of the right dimensions
                            if (!empty($width) && $width >= 100 && $height >= 50)
                            {	
                                    //If image is ok it breaks out of foreach loop
                                    $imagesrc = $image;
                                    break;
                            }		

                    }	

            }

            //Returns Image details.
            return array('src'=>$imagesrc,'width'=>$width,'height'=>$height);

    }
    
    protected function _ProcessLinkTypes($feedid,$scheduleid,$imageid,$promoid)
    {
        
        $types['feedid'] = $feedid;
        $types['scheduleid'] = $scheduleid;
        $types['imageid'] = $imageid;
        $types['promoid'] = $promoid;
        
        if (empty($feedid))
        {
            $types['feedid'] = 0;
        }
        
        if (empty($scheduleid))
        {
            $types['scheduleid'] = 0;
        }
        
        if (empty($imageid))
        {
            $types['imageid'] = 0;
        }
        
        if (empty($promoid))
        {
            $types['promoid'] = 0;
        }
        
        return $types;
        
    }

	protected function _GetCache($uid)
	{
		$count = $this->dbbind->CountCache($uid);
		
		if ($count)
		{
			$data = $this->dbbind->GetCache($uid);
		}
		
		$lang = json_decode($data[1]); 
		$avg = json_decode($data[2]);
		$spam = json_decode($data[3]);
		
		//$this->errorschutney->DebugArray($lang);
		
		$cache['lang'] = $lang[0];
		//$cache['lang'] = $lang->en;
		$cache['hundred'] = $avg->hundred;
		$cache['fr250'] = $avg->fr250;
		$cache['spam1'] = $spam[0];
		$cache['spam2'] = $spam[1];
		
		return $cache;
	}
	
	protected function _GetAllCache($uid)
	{
		$count = $this->dbbind->CountCache($uid);
		
		if ($count)
		{
			$data = $this->dbbind->GetCache($uid);
		}
		
		$cache['lang'] = json_decode($data[1]); 
		$cache['avg'] = json_decode($data[2]);
		$cache['spam'] = json_decode($data[3]);
		
		//$this->errorschutney->DebugArray(json_decode($data[3]));
		
		return $cache;
	}
	
	public function _UpdateCache($uid,$langs,$avg,$spam)
	{
		$count = $this->dbbind->CountCache($uid);
		
		$langs = json_encode($langs);
		$avg = json_encode($avg);
		$spam = json_encode($spam);
		
		if ($count)
		{
			$this->dbbind->UpdateCache($uid,$langs,$avg,$spam,time());	
		}
		else
		{
			$this->dbbind->AddCache($uid,$langs,$avg,$spam,time());
		}
	}

    # End Protected Functions #
    
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>