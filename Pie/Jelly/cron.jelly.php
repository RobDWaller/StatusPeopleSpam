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
            $records = $this->dbbind->GetUsersToCheck(3);
            
//            $this->errorschutney->PrintArray($records);
            
            if (!empty($records))
            {
                foreach ($records as $r)
                {
                    $spam = array();
                    
                    $details = $this->dbbind->GetTwitterDetails($r['userid']);
                    
                    $search = $this->validationchutney->StripNonAlphanumeric($r['screen_name']);
            
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
                                                elseif($ffratio<10&&empty($follower['website'])&&$follower['favourites']==0)
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

    //                        $this->errorschutney->PrintArray($results);

    //                        $rb = ($checks*100)-100;
    //                        $rt = $checks*100;

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
                            }
                            else
                            {
#                                $to = 'rdwaller1984@googlemail.com';
#                                $subject = 'StatusPeople Failed Fakers Cache';
#                                $message = '<p>Dear Rob,<p><p>This is a missed fakers cache score...</p><pre>'.print_r($bio,true).
.'</pre><p>Thanks, StatusPeople</p>';
#                                $headers['from'] = 'StatusPeople <info@statuspeople.com>';
#                                $headers['reply'] = 'info@statuspeople.com';
#                                $headers['return'] = 'info@statuspeople.com';
#
#                                $this->emailchutney->SendEmail($to,$subject,$message,$headers);

                                $this->dbbind->AddSpamError(print_r($bio,true),print_r($bio,true),1,time());
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
                   
                    }
                    
                    unset($spam);
                    unset($chcks);
                    unset($hndrds);
                    unset($fids);
                    unset($results);
                    
                    $update = $this->dbbind->UpdateUsersToCheckTime($r['twitterid'],$r['screen_name'],time());
                }
            }
        }
//        else
//        {
//            echo 'No Match';
//        }
    }
    
//    public function TestFakersCheck()
//    {
//        $records = $this->dbbind->GetUsersToCheck(1);
//            
////            $this->errorschutney->PrintArray($records);
//
//        if (!empty($records))
//        {
//            foreach ($records as $r)
//            {
//                $spam = array();
//
//                $details = $this->dbbind->GetTwitterDetails($r['userid']);
//
//                $search = $this->validationchutney->StripNonAlphanumeric($r['screen_name']);
//
//                $bio = $this->twitterbind->GetUserByScreenName($details[2],$details[3],$search);
//
//                $this->errorschutney->PrintArray($bio);
//
////                    $uid = $bio['user']->id;
//
////                    $spamrecords = $this->dbbind->GetSpamDetails($uid);
//
//                $followers = $bio['user']->followers_count; 
//
//                $requests = round($followers/5000);
//
//                if ($requests > 7)
//                {
//                    $requests = 7;
//                }
//                elseif ($requests < 1)
//                {
//                    $requests = 1;
//                }
//
//                $a = 0;
//                $c = 1;
//                $cursor = '-1';
//
//                while ($c <= $requests)
//                {
//                    $fids[$a] = $this->twitterbind->GetFollowerIDsByName($details[2],$details[3],$search,$cursor);
//
//                    $cursor = $fids[$a]->next_cursor_str;
//
//                    $a++;
//                    $c++;
//                }
//
//                $h = 0; 
//                $i = 0;
//
//                $this->errorschutney->PrintArray($fids);
//
//                foreach ($fids as $ids)
//                {
////                    $this->errorschutney->DebugArray($ids);
//
//                    if (!isset($ids->errors)&&!isset($ids->error)&&!empty($ids->ids))
//                    {
//
//                        foreach ($ids->ids as $id)
//                        {
//                            $hndrds[$h][] = $id; 
//
//                            if ($i == 99)
//                            {
//                                $h++;
//                                $i = 0;
//                            }
//                            else
//                            {
//                                $i++;
//                            }
//                        }
//
//                    }
//                }
//
//                $h++;
//
////                echo $h.'<br/>';
//
//                $this->errorschutney->PrintArray($hndrds);
//
//                if (!empty($hndrds))
//                {
//                    if ($h < 5)
//                    {
//                        $checks = $h;
//                    }
//                    elseif ($h >= 5 && $h < 100)
//                    {
//                        $checks = 5;
//                    }
//                    elseif ($h > 100)
//                    {
////                            $checks = 8;
//                        $checks = 7;
//                    }
////                        elseif ($h >= 300)
////                        {
////                            $checks = 10;
////                        }
//
//                    $incr = round($h/$checks,0,PHP_ROUND_HALF_DOWN);
//
//                    if ($incr < 2)
//                    {
//                        $incr = 1;
//                    }
//
//                    //echo $incr.'<br/>';
//
//                    $y = 0;
//                    $z = 1;
//
//                    echo $h;
//                    echo $checks;
//
//                    while ($z <= $checks)
//                    {
//                        $chcks[$y] = round($y*$incr);  
//
//                        $y++;
//                        $z++;
//                    }
//
//                    $this->errorschutney->PrintArray($chcks);
//
//                    $c = 0;
//                    $sc = 0;
//                    $p = 0;
//
//                    foreach ($chcks as $ch)
//                    {
//                        if (!empty($hndrds[$ch]))
//                        {
//                            $followerdetails = $this->twitterbind->GetFollowersListByArray($details[2],$details[3],$hndrds[$ch],100);
//
////                            $this->errorschutney->PrintArray($followerdetails);
//                            
//                            if ($followerdetails!=false)
//                            {
//
//                                foreach ($followerdetails as $follower)
//                                {
//
//    //                                    $this->errorschutney->PrintArray($follower);
//
//                                    $ffratio = 0;
//
//                                    if ($follower['friends']>0)
//                                    {
//                                        $ffratio = round(($follower['followers']/$follower['friends'])*100); 
//                                    }
//
//                                    if ($ffratio < 20)
//                                    {
//                                        if ($follower['tweets']==0||$follower['followers']==0)
//                                        {
//                                            $sc++;
//    //                                        $this->errorschutney->PrintArray($follower);
//                                            $spam[] = $follower;
//                                        }
//                                        elseif($ffratio<=2)
//                                        {
//                                            $sc++;
//    //                                        $this->errorschutney->PrintArray($follower);
//                                            $spam[] = $follower;
//                                        }
//                                        else 
//                                        {
//                                            $p++;
//                                        }
//
//                                    }
//                                    elseif($follower['followers'] < 20&&$follower['friends']<20&&$follower['tweets']<20)
//                                    {
//                                        $p++;
//                                    }
//                                    $c++;
//                                }
//                            }
//                            else
//                            {
//                                $this->errorschutney->PrintArray('Fail!!');
//                            }
//                        }
//                    }
//
//                    $results['followers']=$followers;
//                    $results['checks']=$c;
//                    $results['potential']=$p;
//                    $results['spam']=$sc;
//
//                    $this->errorschutney->PrintArray($results);
//
////                        $rb = ($checks*100)-100;
////                        $rt = $checks*100;
//
//                    if ($results['followers']>500)
//                    {
//                        $cks = $checks*100;
//                    }
//                    else
//                    {
//                        $cks = $results['followers'];
//                    }
//
////                    if ($results['checks']==$cks)
////                    {
////                        $this->dbbind->AddCheckScore($bio['user']->id,$bio['user']->screen_name,$results['spam'],$results['potential'],$results['checks'],$results['followers'],time());
////                    }
////                    else
////                    {
////                        $to = 'rdwaller1984@googlemail.com';
////                        $subject = 'StatusPeople Failed Fakers Cache';
////                        $message = '<p>Dear Rob,<p><p>This is a missed fakers cache score...</p><pre>'.print_r($bio,true).print_r($results,true).'</pre><p>Thanks, StatusPeople</p>';
////                        $headers['from'] = 'StatusPeople <info@statuspeople.com>';
////                        $headers['reply'] = 'info@statuspeople.com';
////                        $headers['return'] = 'info@statuspeople.com';
////
////                        $this->emailchutney->SendEmail($to,$subject,$message,$headers);
////                    }
//
//                    $s = 0;
//
////                        $this->errorschutney->PrintArray($spam);
//
////                    $insertstring = '';
//
////                    foreach ($spam as $spm)
////                    {
////                        if ($s < 20)
////                        {
////                            $insertstring .= '('.$bio['user']->id.','.$spm['id'].',"'.$spm['screen_name'].'","'.$spm['image'].'",'.time().'),';
////                        }
////
////                        $s++;
////                    }
////
////                    $is = substr($insertstring,0,-1);
////
//////                        $this->errorschutney->PrintArray($is);
////
////                    $this->dbbind->AddFakes($is);
//
//                }
//
//                unset($spam);
//                unset($chcks);
//                unset($hndrds);
//                unset($fids);
//                unset($results);
//
//                $update = $this->dbbind->UpdateUsersToCheckTime($r['twitterid'],$r['screen_name'],time());
//            }
//        }
//    }
    
}
?>