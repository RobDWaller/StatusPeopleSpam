<?php

class API extends Jelly
{
    
    # Header #
    
    private $ResponseFormat;
    
    # End Header #
    
    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin:http://test.statuspeople.com');
    }
    
    # Public Functions #
    
    # Twitter #
    
    public function GetTwitterBio($vars)
    {
        
        $this->ResponseFormat = $vars['rf'];
        $twid = $vars['twid'];
        
//        $this->ResponseFormat = 'json';
//        $twid = '31386162';
        
        $this->_CheckForResponseFormat();
        
        if ($twid)
        {
            $details = $this->dbbind->GetTwitterDetails($twid);
            
//            $this->errorschutney->PrintArray($details);
            
            $bio = $this->twitterbind->GetUserByID($details[2],$details[3],$twid);
            
//            $this->errorschutney->DebugArray($bio);
            
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
            
            if ($spamrecords[7]<$Days1)
            {    
//            if ($true)
//            {
            
                //die('Hello!!');
                
                $followers = $bio['user']->followers_count; 

                $requests = round($followers/5000);
                
                if ($requests > 7)
                {
                    $requests = 7;
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
                
                $h++;
                
//                echo $h.'<br/>';
                
//                $this->errorschutney->PrintArray($hndrds);
                
                if (!empty($hndrds))
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
//                    elseif ($h >= 300)
//                    {
//                        $checks = 10;
//                    }

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
                                        }
                                        elseif($ffratio<=2)
                                        {
                                            $sc++;
    //                                        $this->errorschutney->PrintArray($follower);
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
                    
//                    $this->errorschutney->DebugArray($results);
                    
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
    
    public function GetSpamScoresTest($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $vars['usr'];
        $search = $vars['srch'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$search)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
            
//            $this->errorschutney->PrintArray($details);
            
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
            
                //die('Hello!!');
                
                $followers = $bio['user']->followers_count; 

                $requests = round($followers/5000);
                
                if ($requests > 7)
                {
                    $requests = 7;
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
                    
                    $this->errorschutney->PrintArray($idslist['code']);
                    $this->errorschutney->PrintArray($idslist['data']);
                    
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
                
                $h++;
                
//                echo $h.'<br/>';
                
//                $this->errorschutney->PrintArray($hndrds);
                
                if (!empty($hndrds))
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
//                    elseif ($h >= 300)
//                    {
//                        $checks = 10;
//                    }

                    $incr = round($h/$checks,0,PHP_ROUND_HALF_DOWN);

                    if ($incr < 2)
                    {
                        $incr = 1;
                    }

                    //echo $incr.'<br/>';

                    $y = 0;
                    $z = 1;

                    echo $h;
                    echo $checks;
                    
                    while ($z <= $checks)
                    {
                        $chcks[$y] = round($y*$incr);  

                        $y++;
                        $z++;
                    }

//                    $this->errorschutney->PrintArray($chcks);

                    $c = 0;
                    $sc = 0;
                    $p = 0;
                    
                    foreach ($chcks as $ch)
                    {
                        echo $ch.'|';
                        
                        if (!empty($hndrds[$ch]))
                        {
                            
                            $fllwrs = false;
                            $fcnt = 0;
                            
                            while(!$fllwrs)
                            {
                                if ($fcnt < 3)
                                {
                                    $followerdetails = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$hndrds[$ch],100);
                                    
                                    $this->errorschutney->DebugArray($followerdetails);
                                    
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
                                        }
                                        elseif($ffratio<=2)
                                        {
                                            $sc++;
    //                                        $this->errorschutney->PrintArray($follower);
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
                    
                    $this->errorschutney->DebugArray($results);
                    
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
            
                //die('Hello!!');
                
                $followers = $bio['user']->followers_count; 

                $requests = round($followers/5000);
                
                if ($requests > 20)
                {
                    $requests = 20;
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
                    $fids[$a] = $this->twitterbind->GetFollowerIDsByName($details[2],$details[3],$search,$cursor);
                    
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
                
//                echo $h.'<br/>';
                
//                $this->errorschutney->PrintArray($hndrds);
                
                if (!empty($hndrds))
                {
                    if ($h < 5)
                    {
                            $checks = $h;
                    }
                    elseif ($h >= 5 && $h < 100)
                    {
                            $checks = 5;
                    }
                    elseif ($h >= 100 && $h < 300)
                    {
                            $checks = 8;
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

                    while ($z <= $checks)
                    {
                        $chcks[$y] = round($y*$incr);  

                        $y++;
                        $z++;
                    }

//                    $this->errorschutney->PrintArray($chcks);

                    foreach ($chcks as $ch)
                    {
                        $followerdetails = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$hndrds[$ch],100);

                        if ($followerdetails!=false)
                        {

                            foreach ($followerdetails as $follower)
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
                                    }
                                    elseif($ffratio<=2)
                                    {
                                        $sc++;
//                                        $this->errorschutney->PrintArray($follower);
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
                    
                    $results['followers']=$followers;
                    $results['checks']=$c;
                    $results['potential']=$p;
                    $results['spam']=$sc;
                    
//                    $this->errorschutney->DebugArray($results);
                    
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

//                    $this->errorschutney->PrintArray($bio);

//                    $uid = $bio['user']->id;

//                    $spamrecords = $this->dbbind->GetSpamDetails($uid);


            $followers = $bio['user']->followers_count; 

            $requests = round($followers/5000);

            if ($requests > 20)
            {
                $requests = 20;
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
                $fids[$a] = $this->twitterbind->GetFollowerIDsByName($details[2],$details[3],$search,$cursor);

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

//                echo $h.'<br/>';

//                $this->errorschutney->PrintArray($hndrds);

            if (!empty($hndrds))
            {
                if ($h < 5)
                {
                    $checks = $h;
                }
                elseif ($h >= 5 && $h < 100)
                {
                    $checks = 5;
                }
                elseif ($h >= 100 && $h < 300)
                {
                    $checks = 8;
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
                $p = 0;

                foreach ($chcks as $ch)
                {
                    if (!empty($hndrds[$ch]))
                    {
                        $followerdetails = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$hndrds[$ch],100);

                        if ($followerdetails!=false)
                        {

                            foreach ($followerdetails as $follower)
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

                        $this->errorschutney->PrintArray($results);
                        $this->errorschutney->DebugArray($spam);

                $rb = ($checks*100)-100;
                $rt = $checks*100;

                if ($results['checks']<=$rt&&$results['checks']>=$rb)
                {
                    $this->dbbind->AddCheckScore($bio['user']->id,$bio['user']->screen_name,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
                }

                $s = 0;

//                        $this->errorschutney->PrintArray($spam);

                foreach ($spam as $spm)
                {
                    if ($s < 20)
                    {
                        $insertstring .= '('.$bio['user']->id.','.$spm['id'].',"'.$spm['screen_name'].'","'.$spm['image'].'",'.time().'),';
                    }

                    $s++;
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
    
    public function GetSpamScoresOverTime($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $vars['usr'];
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $scores = $this->dbbind->GetScoresOverTime($user,20);
            
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
                
                if ($i<9)
                {
                    
                    $d = 9 - $i;
                    
                    $c = 0;
                    
                    $t = 1;
                    $day = 86400;
                    
                    while ($c<$d)
                    {
                        $date = date('M d',$created-($t*$day));
                        
                        $data['Fake'][$i] = array('count'=>$fake,'date'=>$date);
                        $data['Inactive'][$i] = array('count'=>$inactive,'date'=>$date);
                        $data['Good'][$i] = array('count'=>$good,'date'=>$date);
                        
                        $c++;
                        $t++;
                        $i++;
                    }
                    
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
        $user = $vars['usr'];
        
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
    
    public function GetCompetitorCount($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $vars['usr'];
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $competitors = $this->dbbind->GetCompetitorCount($user);
            
            if ($competitors>=0)
            {
                $this->_APISuccess(201, 'Data returned successfully.',$competitors);
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
        $user = $vars['usr'];
        
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
        $user = $vars['usr'];
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
        $user = $vars['usr'];
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
        $user = $vars['usr'];
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
        $user = $vars['usr'];
        
        $this->_CheckForResponseFormat();
        
        if ($user)
        {
            $fakes = $this->dbbind->GetFakes($user,5);
            
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
    
    public function GetUpdateFakersList($vars)
    {
        $this->ResponseFormat = $vars['rf'];
        $user = $vars['usr'];
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


            $followers = $bio['user']->followers_count; 

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

            $h++;

//                echo $h.'<br/>';

//                $this->errorschutney->PrintArray($hndrds);

            if (!empty($hndrds))
            {
                if ($h < 5)
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

                if ($results['checks']==$cks)
                {
                    $this->dbbind->AddCheckScore($bio['user']->id,$bio['user']->screen_name,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
                    $update = $this->dbbind->UpdateUsersToCheckTime($r['twitterid'],$r['screen_name'],time());
                }

                $s = 0;

//                        $this->errorschutney->PrintArray($spam);

                $insertstring = '';

                foreach ($spam as $spm)
                {
                    if ($s < 20)
                    {
                        $insertstring .= '('.$bio['user']->id.','.$spm['id'].',"'.$spm['screen_name'].'","'.$spm['image'].'",'.time().'),';
                    }

                    $s++;
                }

                $is = substr($insertstring,0,-1);

//                        $this->errorschutney->PrintArray($is);

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
    
    public function PostTweet()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $_POST['usr'];
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
        $user = $_POST['usr'];
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
            $this->_APIFail(400,'No data submitted.');
        }
    }

    public function PostDeleteFaker()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $_POST['usr'];
        $twid = $_POST['twid'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$twid)
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
            $this->_APIFail(400,'No data submitted.');
        }
    }

    public function PostBlockSpam()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $_POST['usr'];
        $twid = $_POST['twid'];
        
        $this->_CheckForResponseFormat();
        
        if ($user&&$twid)
        {
            $details = $this->dbbind->GetTwitterDetails($user);
            
            $destroy = $this->twitterbind->DestroyFriendship($details[2],$details[3],$twid);
            
//            $this->errorschutney->DebugArray($destroy);
            
            if ($destroy)
            {
                $block = $this->dbbind->BlockSpam($user,$twid);
                
                if ($block)
                {    
                    $this->_APISuccess(201, 'User successfully blocked.',$destroy);
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
    
    public function PostNotSpam()
    {
        $this->ResponseFormat = $_POST['rf'];
        $user = $_POST['usr'];
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
    
    # End Twitter #
    
    # End Public Function #
    
    
    
    # Protected Functions #
    
    protected function _CompleteFail()
    {
        header('Content-type: application/rss+xml');
        
        $output['xml'] = $this->xmlchutney->XMLAPIError(400,'#FAIL, No Response Format!!');

        $this->glaze->view('API/xml.php',$output);
        
        die();
    }
    
    protected function _APIFail($code,$message)
    {
        
        if ($this->ResponseFormat == 'json')
        {
            $output['json'] = $this->jsonchutney->JSONAPIError($code,$message);

            $this->glaze->view('API/json.php',$output);
        }
        elseif ($this->ResponseFormat == 'xml')
        {
            header('Content-type: application/rss+xml');

            $output['xml'] = $this->xmlchutney->XMLAPIError($code,$message);

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


    # End Protected Functions #
    
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
