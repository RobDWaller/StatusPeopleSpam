<?php

class TwitterHelper
{
    
    public function ProcessTimelineData($tweets,$timezone)
    {
        if (!empty($tweets))
        {
            foreach ($tweets as $tweet)
            {
                $timeline[] = array('id'=>$tweet->id_str,
                            'name'=>$tweet->user->screen_name, 
                            'userid'=>$tweet->user->id_str,
                            'following'=>$tweet->user->following,
                            'avatar'=>$tweet->user->profile_image_url,
                            'tweet'=>$tweet->text,
                            'source'=>$tweet->source,
                            'date'=>DateAndTime::SetTime('Y/m/d H:i:s',strtotime($tweet->created_at),$timezone),
                            'inreplyto'=>$tweet->in_reply_to_status_id_str);

            }

            return $timeline;
        }
    }
    
    public function ProcessSearchData($tweets,$timezone)
    {
        if (!empty($tweets))
        {
            foreach ($tweets as $tweet)
            {
                $timeline[] = array('id'=>$tweet->id_str,
                            'name'=>$tweet->from_user, 
                            'userid'=>$tweet->id_str,
                            'following'=>false,
                            'avatar'=>$tweet->profile_image_url,
                            'tweet'=>$tweet->text,
                            'source'=>html_entity_decode($tweet->source),
                            'date'=>DateAndTime::SetTime('Y/m/d H:i:s',strtotime($tweet->created_at),$timezone),
                            'inreplyto'=>(isset($tweet->in_reply_to_status_id_str)?$tweet->in_reply_to_status_id_str:0));

            }

            return $timeline;
        }
    }
    
    public function ProcessRetweetData($retweets,$timezone)
    {
        
        if (!empty($retweets))
        {
        
            foreach ($retweets as $tweet)
            {

                    $timeline[] = array('id'=>$tweet->id_str,
                            'name'=>$tweet->user->screen_name, 
                            'userid'=>$tweet->user->id_str,
                            'avatar'=>$tweet->user->profile_image_url,
                            'tweet'=>$tweet->text,
                            'source'=>$tweet->source,
                            'date'=>DateAndTime::SetTime('Y/m/d H:i', strtotime($tweet->created_at),$timezone),
                            'inreplyto'=>$tweet->in_reply_to_status_id_str,
                            'retweetcount'=>$tweet->retweet_count);

            }

            return $timeline;
        
        }
        
    }
    
    public function ProcessTwitterBio($bio)
    {
        if (!empty($bio))
        {
            $tweetbio = array(
                'id'=>$bio->id_str,
                'created_at'=>$bio->created_at,
                'tweets'=>$bio->statuses_count,
                'listed'=>$bio->listed_count,
                'screenname'=>$bio->screen_name,
                'friends'=>$bio->friends_count,
                'followers'=>$bio->followers_count,
                'name'=>$bio->name,
                'location'=>$bio->location,
                'url'=>$bio->url,
                'image'=>$bio->profile_image_url,
                'description'=>$bio->description,
                'following'=>$bio->following
            );


            return $tweetbio;
        }
    }
    
    public function ProcessUserDetails($user)
    {
        //Errors::DebugArray($user);
        
        if (!empty($user))
        {
            $userdetails['id'] = $user->id_str;
			$userdetails['name'] = $user->name;
			$userdetails['screenname'] = $user->screen_name;
			$userdetails['url'] = $user->url;
			$userdetails['image'] = $user->profile_image_url;
			$userdetails['location'] = $user->location;
			$userdetails['timezone'] = $user->time_zone;
			$userdetails['language'] = $user->lang;
			$userdetails['description'] = $user->description;
			$userdetails['tweets'] = $user->statuses_count;
			$userdetails['followers'] = $user->followers_count;
			$userdetails['friends'] = $user->friends_count;
			$userdetails['listed'] = $user->listed_count;
			$userdetails['favourites'] = $user->favourites_count;
			$userdetails['daysactive'] = number_format(round((((time() - strtotime($user->created_at))/60)/60)/24,0));
			$userdetails['following'] = ($user->following==true?1:0);
			$userdetails['created'] = round(((time()/3600)/24)-((strtotime($user->created_at)/3600)/24));
			$userdetails['lasttweet'] = round(((time()/3600)/24)-((strtotime($user->status->created_at)/3600)/24));
			$userdetails['tweet'] = $user->status->text;
			$userdetails['tweet_retweet'] = $user->status->retweet_count;
			$userdetails['tweet_favorite'] = $user->status->favorite_count;
			$tpd = 0;
			if ($user->statuses_count>0&&$userdetails['created']>0)
			{
				$tpd = round($user->statuses_count/$userdetails['created'],2);
			}
			$userdetails['tweetsperday'] = $tpd;
			//$userdetails['spam'] = self::_IsUserSpam(array('followers'=>$user->followers_count,'friends'=>$user->friends_count,'tweets'=>$user->statuses_count,'website'=>$user->url,'favourites'=>$user->favourites_count));
			$userdetails['spam'] = self::_IsUserSpam($userdetails);
			
			$userdetails['tweets'] = number_format($user->statuses_count);
			$userdetails['followers'] = number_format($user->followers_count);
			$userdetails['friends'] = number_format($user->friends_count);
			$userdetails['listed'] = number_format($user->listed_count);
			$userdetails['favourites'] = number_format($user->favourites_count);
            
            return $userdetails;
        }
        
    }
    
    public function ProcessUsersDetails($users)
    {
        //Errors::DebugArray($user);
        foreach ($users as $user)
        {
            if (!empty($user))
            {
				//Errors::PrintArray($user);
			
                $userdetails['id'] = $user->id_str;
				$userdetails['name'] = $user->name;
				$userdetails['screenname'] = $user->screen_name;
				$userdetails['url'] = $user->url;
				$userdetails['image'] = $user->profile_image_url;
				$userdetails['location'] = $user->location;
				$userdetails['timezone'] = $user->time_zone;
				$userdetails['language'] = $user->lang;
				$userdetails['description'] = $user->description;
				$userdetails['tweets'] = $user->statuses_count;
				$userdetails['followers'] = $user->followers_count;
				$userdetails['friends'] = $user->friends_count;
				$userdetails['listed'] = $user->listed_count;
				$userdetails['favourites'] = $user->favourites_count;
				$userdetails['daysactive'] = number_format(round((((time() - strtotime($user->created_at))/60)/60)/24,0));
				$userdetails['following'] = ($user->following==true?1:0);
				$userdetails['created'] = round(((time()/3600)/24)-((strtotime($user->created_at)/3600)/24));
				$userdetails['lasttweet'] = round(((time()/3600)/24)-((strtotime($user->status->created_at)/3600)/24));
				$userdetails['tweet'] = $user->status->text;
				$userdetails['tweet_retweet'] = $user->status->retweet_count;
				$userdetails['tweet_favorite'] = $user->status->favorite_count;
				$tpd = 0;
				if ($user->statuses_count>0&&$userdetails['created']>0)
				{
					$tpd = round($user->statuses_count/$userdetails['created'],2);
				}
				$userdetails['tweetsperday'] = $tpd;
				//$userdetails['spam'] = self::_IsUserSpam(array('followers'=>$user->followers_count,'friends'=>$user->friends_count,'tweets'=>$user->statuses_count,'website'=>$user->url,'favourites'=>$user->favourites_count));
				$userdetails['spam'] = self::_IsUserSpam($userdetails);
				
				$userdetails['tweets'] = number_format($user->statuses_count);
				$userdetails['followers'] = number_format($user->followers_count);
				$userdetails['friends'] = number_format($user->friends_count);
				$userdetails['listed'] = number_format($user->listed_count);
				$userdetails['favourites'] = number_format($user->favourites_count);
				
                $usersdata[] = $userdetails;
            }
        }
        return $usersdata;
        
    }
	
	public function ProcessSpamUser($user)
	{
		if (!empty($user))
		{
			$result = array('id'=>$user->id_str,
							  'screen_name'=>$user->screen_name,
							  'location'=>$user->location,
							  'timezone'=>$user->time_zone,
							  'language'=>$user->lang,
							  'friends'=>$user->friends_count,
							  'followers'=>$user->followers_count,
							  'tweets'=>$user->statuses_count,
							  'tweetsperday'=>$tpd,
							  'description'=>$user->description,
							  'website'=>$user->url,
							  'image'=>$user->profile_image_url,
							  'following'=>$user->following,
							  'favourites'=>$user->favourites_count,
							  'listed'=>$user->listed_count,
							  'created'=>$created,
							  'lasttweet'=>$lasttweet,
							  'tweet'=>$user->status->text,
							  'tweet_retweet'=>$user->status->retweet_count,
							  'tweet_favorite'=>$user->status->favorite_count);
		}
		
		return $result;
	}
	
	protected function _IsUserSpam($user)
    {
        $ffratio = 0;
        
        //Errors::PrintArray($user);	
		
        $result = 'Good';

        // if ($user['friends']>0)
        // {
            // $ffratio = round(($user['followers']/$user['friends'])*100); 
        // }

        // if ($ffratio < 20)
        // {
            // if ($user['tweets']==0||$user['followers']==0)
            // {
                // $result = 'Fake';
            // }
            // elseif($ffratio<=2)
            // {
                // $result = 'Fake';
            // }
            // elseif($ffratio<10&&empty($user['website'])&&$user['favourites']==0)
            // {
                // $result = 'Fake';                       
            // }
            // else 
            // {
                // $result = 'Inactive';
            // }

        // }
        // elseif($user['followers']<20&&$user['friends']<20&&$user['tweets']<20)
        // {
            // $result = 'Inactive';
        // }
        
		$api = new API();
		
		$check = $api->_GetFakerStatus($user);
		
		//Errors::DebugArray($status);	
		
		if ($check['status']==1)
		{
			$result = 'Fake';
		}
		elseif ($check['status']==2)
		{
			$result = 'Inactive';
		}
		
        return $result;
    }
    
}

?>