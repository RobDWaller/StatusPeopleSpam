<?php

class FeedsHelper
{
    
    public function ProcessFeeds($feeds)
    {
        if (!empty($feeds))
        {
            $i = 0;
            
            foreach ($feeds as $feed)
            {
                $data[$i]['feedid'] = $feed['id'];
                $data[$i]['feedname'] = $feed['name'];
                $data[$i]['feedurl'] = $feed['url'];
                $data[$i]['created'] = date('Y/m/d H:i:s',$feed['created']);
                
                $i++;
            }
            
            return $data;
            
        }
    }
    
    public function ProcessFeedPosts($feedposts,$timezone)
    {
        
        if (!empty($feedposts))
        {
            $i = 0;
            
            foreach ($feedposts as $post)
            {
                $posts[$i]['feedname'] = $post['name'];
                $posts[$i]['feedurl'] = $post['url'];
                $posts[$i]['postid'] = $post['id'];
                $posts[$i]['posttitle'] = $post['title'];
                $posts[$i]['postdescription'] = $post['description'];
                $posts[$i]['postlink'] = $post['link'];
                $posts[$i]['published'] = DateAndTime::SetTime('Y/m/d H:i:s',strtotime($post['pubdate']),$timezone);
                
                $i++;
            }
            
            return $posts;
        }
        
    }
    
}

?>