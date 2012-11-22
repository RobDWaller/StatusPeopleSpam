<?php

class FacebookHelper
{
    
    public function ProcessPageStream($pagestream,$timezone)
    {
        if (!empty($pagestream['data']))
        {
        
            $page = array();
            
            $i = 0;
            
            foreach ($pagestream['data'] as $mes)
            {

                    //$this->errorschutney->PrintArray($mes);

                    $page[$i]['postid'] = $mes['id'];
                    $page[$i]['userid'] = $mes['from']['id'];
                    $page[$i]['userimage'] = "http://graph.facebook.com/".$mes['from']['id']."/picture";
                    $page[$i]['username'] = $mes['from']['name'];
                    $page[$i]['message'] = $mes['message'];
                    $page[$i]['createdtime'] = DateAndTime::SetTime('Y/m/d H:i:s',strtotime($mes['created_time']),$timezone);
                    $page[$i]['islink'] = 0;
                    
                    if ($mes['type'] == 'link' || $mes['type'] == 'swf')
                    {
                            $page[$i]['islink'] = 1;
                            $page[$i]['linkpicture'] = $mes['picture'];
                            $page[$i]['link'] = $mes['link'];
                            $page[$i]['linkname'] = $mes['name'];
                            $page[$i]['linkdescription'] = $mes['description'];
                            
                    }
                    
                    $page[$i]['isphoto'] = 0;
                    
                    if ($mes['type'] == 'photo')
                    {
                        $page[$i]['isphoto'] = 1;
                        $page[$i]['photo'] = $mes['picture'];
                        $page[$i]['photolink'] = $mes['link'];
                        $page[$i]['photoname'] = $mes['name'];
                    }
                    
                    $page[$i]['haslikes']=0;
                    $page[$i]['hascomments']=0;
                    
                    if (is_array($mes['likes']['data']) || is_array($mes['comments']['data']))  
                    {

                            if (is_array($mes['likes']['data']))
                            {
                                    $page[$i]['haslikes'] = 1;
                                    $page[$i]['likes'] = $mes['likes']['count'];
                                    $page[$i]['latestlike'] = $mes['likes']['data'][0]['name'];
                            }

                            if (is_array($mes['comments']['data']))
                            {
                                    
                                    $page[$i]['hascomments'] = 1;
                                    $page[$i]['comments'] = $mes['comments']['count'];
                                
                                    $c = 0;
                                    
                                    foreach ($mes['comments']['data'] as $com)
                                    {
                                            $page[$i]['commentdata'][$c]['commentid'] = $com['id'];
                                            $page[$i]['commentdata'][$c]['userid'] = $com['from']['id'];
                                            $page[$i]['commentdata'][$c]['username'] = $com['from']['name'];
                                            $page[$i]['commentdata'][$c]['message'] = $com['message'];
                                            $page[$i]['commentdata'][$c]['created'] = DateAndTime::SetTime('Y/m/d H:i:s',strtotime($com['created_time']),$timezone);
                                    }

                            }

                           
                    }	

                    $i++;
                    
            }	
            
            return $page;

        }
    }
    
    public function ProcessPageComments($pagestream,$timezone)
    {
        if (!empty($pagestream['data']))
        {
            $i = 0;
            
            foreach ($pagestream['data'] as $mes)
            {
                if (is_array($mes['comments']['data']))
                {
                        foreach ($mes['comments']['data'] as $com)
                        {
                                $comments[$i]['commentid'] = $com['id'];
                                $comments[$i]['userid'] = $com['from']['id'];
                                $comments[$i]['username'] = $com['from']['name'];
                                $comments[$i]['message'] = $com['message'];
                                $comments[$i]['created'] = DateAndTime::SetTime('Y/m/d H:i:s',strtotime($com['created_time']),$timezone);
                                
                                $i++;
                        }

                }
            }
            
            return $comments;
        }
    }
    
    public function ProcessPostComments($commentstream,$timezone)
    {
        
        if (!empty($commentstream['comments']['data']))
        {
            $i=0;
            
            foreach ($commentstream['comments']['data'] as $comment)
            {
                
                $com[$i]['commentid'] = $comment['id'];
                $com[$i]['userid'] = $comment['from']['id'];
                $com[$i]['username'] = $comment['from']['name'];
                $com[$i]['userimage'] = "http://graph.facebook.com/".$comment['from']['id']."/picture";
                $com[$i]['message'] = $comment['message'];
                $com[$i]['created'] = DateAndTime::SetTime('Y/m/d H:i:s',strtotime($comment['created_time']),$timezone);
                
                $i++;
            }
            
            return $com;
            
        }
                
    }
    
}

?>