<?php

class TwitterRequests
{

	public $twitter;

	function __construct()
	{
		//require_once(__SITE_PATH.'/Pie/Pork/twittermodel/config.php');
		require_once(__SITE_PATH.'/Pie/Pork/twittermodel/twitteroauth/twitteroauth.php');
	}	
	
//	public function GetReplies($token,$secret,$count)
//	{
//		
//		$this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
//		
//		$replies = $this->twitter->get('statuses/mentions',array('count'=>$count));
//		
//		return $replies;
//		
//	}
	
	public function RateLimit($token,$secret,$resources)
	{
		$this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
		
		$limits = $this->twitter->get('application/rate_limit_status',array('resources'=>$resources));
		
		return $limits;
	}
	
	public function GetTwitterTimeline($type,$token,$secret,$count,$sinceid = null)
	{
		
		$this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
		
                if ($sinceid == null)
                {
                    $feed = $this->twitter->get('statuses/'.$type,array('count'=>$count));
                }
                else
                {
                    $feed = $this->twitter->get('statuses/'.$type,array('count'=>$count,'since_id'=>$sinceid));
                    
//                    $headers['from'] = 'StatusPeople <info@statuspeople.com>';
//                    $headers['reply'] = 'info@statuspeople.com';
//                    $headers['return'] = 'info@statuspeople.com';
//                    
//                    Email::SendEmail('rdwaller1984@googlemail.com','Twitter Test',print_r($feed,true).' '.$this->twitter->http_code,$headers);
                }
                
                $code = $this->twitter->http_code;
                
                $result['responsecode'] = $code;
                $result['feed'] = $feed;
                
                return $result;
	}
        
        public function GetTwitterTimelineByUser($token,$secret,$screenname,$count)
	{
		
		
                $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
		$feed = $this->twitter->get('statuses/user_timeline',array('screen_name'=>$screenname,'count'=>$count));
		
		$timeline = array();
		
		if(!empty($feed))
		{
			foreach ($feed as $tweet)
			{
				
				$timeline[] = array('id'=>$tweet->id_str,
					'name'=>$tweet->user->screen_name, 
					'userid'=>$tweet->user->id_str,
					'following'=>$tweet->user->following,
					'avatar'=>$tweet->user->profile_image_url,
					'tweet'=>$tweet->text,
					'source'=>$tweet->source,
					'date'=>$tweet->created_at,
					'inreplyto'=>$tweet->in_reply_to_status_id_str);
				
			}
		}
		return $timeline;
	}
	
        public function GetRetweetData($token,$secret,$count)
        {
                $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
				$feed = $this->twitter->get('statuses/retweets_of_me',array('count'=>$count));
		
				return $feed;
        }
	
		public function GetRetweetDataByID($token,$secret,$id)
		{
			$this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
			$feed = $this->twitter->get('statuses/retweets',array('id'=>$id,'count'=>100));
		
			$result['code'] = $this->twitter->http_code;
			$result['data'] = $feed;
			
			return $result;
		}
        
        public function GetDMData($token,$secret,$count)
        {
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            $messages = $this->twitter->get('direct_messages',array('count'=>$count));
		
            return $messages;
        }


        public function GetTweet($tweetid,$token,$secret)
	{
		
		$this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
		$tweet = $this->twitter->get('statuses/show',array('id'=>$tweetid));
		
		$tweetinfo = array('id'=>$tweet->id_str,
			'name'=>$tweet->user->screen_name, 
			'avatar'=>$tweet->user->profile_image_url,
			'tweet'=>$tweet->text,
			'source'=>$tweet->source,
			'date'=>$tweet->created_at,
			'inreplyto'=>$tweet->in_reply_to_status_id_str);
		
		return $tweetinfo;
		
	}
	
//	public function SendTweet($token,$secret,$tweet)
//	{
//		$this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
//		
//		$result = $this->twitter->post('statuses/update',array('status'=>$tweet));
//		
//		return $result;		
//	}
	
        public function GetUserByID($token,$secret,$userid)
        {
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            
            $result = $this->twitter->get('users/show', array('user_id' => $userid));
            
//            Errors::PrintArray($result);
//            Errors::PrintArray($this->twitter->http_code);
//            Errors::PrintArray($this->twitter->http_header);
//            Errors::DebugArray($this->twitter->http_info);
            
            return $result;
            
        }
        
        public function GetUserByScreenName($token,$secret,$screenname)
        {
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            
            $user = $this->twitter->get('users/show',array('screen_name'=>$screenname));
            
            $code = $this->twitter->http_code;
                
            $result['code'] = $code;
            $result['user'] = $user;
            
            return $result;
        }
        
        public function GetFollowers($token,$secret,$userid)
        {
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            
            $result = $this->twitter->get('statuses/followers', array('user_id' => $userid));
            
            return $result;
            
        }
        
        public function SearchUsers($token,$secret,$query,$pages,$pagecount)
        {
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);	
	
            $search = $this->twitter->get('users/search',array('q'=>$query,'page'=>$pages,'per_page'=>$pagecount));
            
            return $search;
            
        }
        
        public function TweetSearch($token,$secret,$query,$page,$pagecount,$resulttype,$lang)
        {
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);	
		
            $search = $this->twitter->get('search',array('q'=>$query,'page'=>$page,'rpp'=>$pagecount,'result_type'=>$resulttype,'lang'=>$lang));
            
            return $search;            
        }
	
		public function SearchTweets($token,$secret,$query,$count,$resulttype,$lang)
        {
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);    
		
            $search = $this->twitter->get('search/tweets',array('q'=>$query,'count'=>$count,'resulttype'=>$resulttype,'lang'=>$lang));
            
			$result['code'] = $this->twitter->http_code;
			$result['data'] = $search;			
			
            return $result;            
        }
        
        public function CreateFriendship($token,$secret,$friendid)
        {
            
            $result = false;
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            
            $this->twitter->post('friendships/create',array('user_id'=>$friendid));
           
            if ($this->twitter->http_code == 200);
            {
			$result = true;	
            }
		
            return $result;
            
        }
        
        public function DestroyFriendship($token,$secret,$friendid)
        {
            $result = false;
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            
            $destroy = $this->twitter->post('blocks/create',array('user_id'=>$friendid));

			//ERRORS::PrintArray($destroy);
			//ERRORS::PrintArray($this->twitter);

			if ($this->twitter->http_code == 200||$this->twitter->http_code == 404)
            {
				//ERRORS::PrintArray($destroy);
				//ERRORS::PrintArray($this->twitter);
			
				$result = true;	
            }
		
            return $result;
        }
	
		public function Unblock($token,$secret,$friendid)
        {
            $result = false;
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            
            $unblock = $this->twitter->post('blocks/destroy',array('user_id'=>$friendid));
           
			//ERRORS::PrintArray($destroy);
			
            $code = $this->twitter->http_code;
                
            $result['code'] = $code;
            $result['data'] = $unblock;
            
            return $result;
        }
        
        public function SendTweet($token,$secret,$tweet,$replyid = null)
	{
		$result = false;
		
		$this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
		$send = $this->twitter->post('statuses/update',array('status'=>$tweet,'in_reply_to_status_id'=>$replyid));	
                
                //Errors::DebugArray($send);
                
                if (!isset($send->error))
                {
                    if ($this->twitter->http_code == 200);
                    {
                            $result = true;	
                    }
                }
                
		return array('success'=>$result,'id'=>$send->id_str);		
	}
        
        public function GetFollowersList($token,$secret,$name,$count)
        {
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            $followerids = $this->twitter->get('followers/ids',array('screen_name'=>$name,'cursor'=>'-1'));
		
//            Errors::DebugArray($followerids);
            
            $followeridlist = '';
		
            $i = 1;
			
			if (!empty($followerids->ids))
			{
				foreach ($followerids->ids as $id)
				{
						if ($i <= $count)
						{
								$followeridlist .= $id.',';
						}
						$i++;
				}
	
				$followeridlist = substr($followeridlist,0,-1);
	
				$followers = $this->twitter->get('users/lookup',array('user_id'=>$followeridlist));
			}

//            foreach ($followers as $user)
//            {
//                    $followersarray[] = array('id'=>$user->id_str,
//                            'screen_name'=>$user->screen_name,
//                            'location'=>$user->location,
//                            'friends'=>$user->friends_count,
//                            'followers'=>$user->followers_count,
//                            'tweets'=>$user->statuses_count,
//                            'description'=>$user->description,
//                            'website'=>$user->url,
//                            'image'=>$user->profile_image_url,
//                            'following'=>$user->following);	
//            }

            return $followers;
            
        }
        
        public function GetFollowersListByArray($token,$secret,$followerids,$count)
        {
            
            $i = 1;
            
            foreach ($followerids as $id)
            {
                    if ($i <= $count)
                    {
                            $followeridlist .= $id.',';
                    }
                    $i++;
            }

            $followeridlist = substr($followeridlist,0,-1);

            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token, $secret);
            $followers = $this->twitter->post('users/lookup',array('user_id'=>$followeridlist,'include_entities'=>false));
            
			//Errors::DebugArray($followers);
			
            $code = $this->twitter->http_code;
            
            if ($this->twitter->http_code==200)
            {
                
                if (empty($followers))
                {
                    $code = 404;
                }
                else
                {
					//ERRORS::DebugArray($followers);
					
                    foreach ($followers as $user)
                    {
						//Errors::DebugArray($user);
						
						$created = round(((time()/3600)/24)-((strtotime($user->created_at)/3600)/24));
						$lasttweet = round(((time()/3600)/24)-((strtotime($user->status->created_at)/3600)/24));
						
						$tpd = 0;
						if ($user->statuses_count>0&&$created>0)
						{
							$tpd = round($user->statuses_count/$created,2);
						}
						
                            $followersarray[] = array('id'=>$user->id_str,
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
                }
            
            }
            
            return array('code'=>$code,'data'=>$followersarray);
            
        }
        
        public function GetFollowerIDs($token,$secret,$userid,$cursor = '-1')
        {
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token,$secret);
		
            $ids = $this->twitter->get('followers/ids', array('user_id' => $userid,'cursor'=>$cursor));
            
            $code = $this->twitter->http_code;
            
            return array('code'=>$code,'data'=>$ids);
            
        }
        
        public function GetFollowerIDsByName($token,$secret,$screenname,$cursor='-1')
        {
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token,$secret);
		
            $ids = $this->twitter->get('followers/ids', array('screen_name' => $screenname,'cursor'=>$cursor));
            
            $code = $this->twitter->http_code;
            
            return array('code'=>$code,'data'=>$ids);
            
        }
        
        public function DeleteTweet($token,$secret,$tweetid)
        {
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token,$secret);
            
            $delete = $this->twitter->post('statuses/destroy',array('id'=>$tweetid));
            
            return $delete;
        }
        
        public function SendDirectMessage($token,$secret,$screename,$message)
        {
            $result = false;
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token,$secret);
            
            $send = $this->twitter->post('direct_messages/new',array('screen_name'=>$screename,'text'=>$message));
            
            if (!isset($send->error))
            {
                if ($this->twitter->http_code == 200);
                {
                        $result = true;	
                }
            }

            return array('success'=>$result,'id'=>$send->id_str);	
            
        }
        
        public function UpdateBio($token,$secret,$name,$url,$location,$description)
        {
            $result = false;
            
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token,$secret);
            
            $update = $this->twitter->post('account/update_profile',array('name'=>$name,'url'=>$url,'location'=>$location,'description'=>$description));
            
            if (!isset($send->error))
            {
                if ($this->twitter->http_code == 200);
                {
                        $result = true;	
                }
            }
            
            return $result;
            
        }
        
        public function Verify($token,$secret)
        {
            $this->twitter = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $token,$secret);
            
            $verify = $this->twitter->get('account/verify_credentials');
            
			$result['code'] = $this->twitter->http_code;
			$result['data'] = $verify;
			
            return $result;
        }
}

?>