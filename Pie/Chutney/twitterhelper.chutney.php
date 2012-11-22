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
            $userdetails['description'] = $user->description;
            $userdetails['tweets'] = number_format($user->statuses_count);
            $userdetails['followers'] = number_format($user->followers_count);
            $userdetails['friends'] = number_format($user->friends_count);
            $userdetails['listed'] = number_format($user->listed_count);
            $userdetails['favourites'] = number_format($user->favourites_count);
            $userdetails['daysactive'] = number_format(round((((time() - strtotime($user->created_at))/60)/60)/24,0));
            $userdetails['following'] = ($user->following==true?1:0);
            
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
                $userdetails['id'] = $user->id_str;
                $userdetails['name'] = $user->name;
                $userdetails['screenname'] = $user->screen_name;
                $userdetails['url'] = $user->url;
                $userdetails['image'] = $user->profile_image_url;
                $userdetails['location'] = $user->location;
                $userdetails['description'] = $user->description;
                $userdetails['tweets'] = number_format($user->statuses_count);
                $userdetails['followers'] = number_format($user->followers_count);
                $userdetails['friends'] = number_format($user->friends_count);
                $userdetails['listed'] = number_format($user->listed_count);
                $userdetails['favourites'] = number_format($user->favourites_count);
                $userdetails['daysactive'] = number_format(round((((time() - strtotime($user->created_at))/60)/60)/24,0));
                $userdetails['following'] = ($user->following==true?1:0);

                $usersdata[] = $userdetails;
            }
        }
        return $usersdata;
        
    }
    
}

?>