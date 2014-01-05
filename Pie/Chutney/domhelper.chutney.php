<?php

class DomHelper
{
    
    public function ParseXMLString($xml,$query,$children)
    {
        $code = 200;
        $message = 'Query Successful';
        
        if ($xml)
        {

            $dom = new DOMDocument();

            $dom->loadXML($xml);

            $xpath = new DOMXPath($dom);

            $data = $xpath->query($query);

            $i = 0;

            foreach ($data as $entry) {
                foreach($children as $child)
                {
                     $ch = $xpath->query($child,$entry);

                     foreach ($ch as $c)
                     {
                          $result[$i][$child]=$c->nodeValue;
                     }

                }
                $i++;

            }

            
        }
        else
        {
            $code = 500;
            $message = 'XML Data Empty';
        }

        return array('code'=>$code,'message'=>$message,'data'=>$result);
        
    }
    
    public function BuildAPIXML($code,$message,$data)
    {
        $dom = new DOMDocument('1.0','utf-8');
        
        $response = $dom->createElement('response');
        
        $cd = $dom->createElement('code',$code);
        $response->appendChild($cd);
        
        $mssg = $dom->createElement('message',$message);
        $response->appendChild($mssg);
        
        $dt = $dom->createElement('data');
        
        if (!empty($data))
        {
            foreach ($data as $key => $dat)
            {
                if (is_numeric($key))
                {
                    $sub = $dom->createElement('item');
                    
                    $dt->appendChild($sub);
                    
                    foreach ($dat as $k => $d)
                    {
                        if (is_array($d))
                        {
                            foreach($d as $i)
                            {
                                $sub->appendChild($dom->createElement($k,$i));
                            }
                        }
                        else
                        {
                            $sub->appendChild($dom->createElement($k,$d));
                        }
                    }
                          
                }    
                else
                {
                   
                    if (is_array($dat))
                    {
                        $sub = $dom->createElement($key);
    
                        $dt->appendChild($sub);
    
                        foreach ($dat as $k => $d)
                        {
                            if (is_array($d))
                            {
                                foreach($d as $i)
                                {
                                    $sub->appendChild($dom->createElement($k,$i));
                                }
                            }
                            else
                            {
                                if (is_numeric($k))
                                {
                                    $sub->appendChild($dom->createElement('item',$d));
                                }
                                else
                                {
                                    $sub->appendChild($dom->createElement($k,$d));
                                }
                            }
                        }
                    }
                    else
                    {
                        $dt->appendChild($dom->createElement($key,$dat));
                    }
                }
            }
        
        }
        
        $response->appendChild($dt);
        
        $dom->appendChild($response);
                
        return $dom->saveXML();
    }
    
    public function BuildLinkedInShare($data)
    {
        $dom = new DOMDocument('1.0','utf-8');
        
        $share = $dom->createElement('share');
        
        $comment = $dom->createElement('comment',$data['share']['comment']);
        
        $share->appendChild($comment);
        
        if (isset($data['content']))
        {
            
            $content = $dom->createElement('content');
            
            if (isset($data['content']['title'])&&!empty($data['content']['title']))
            {
                $title = $dom->createElement('title',$data['content']['title']);
                
                $content->appendChild($title);
            }
            
            if (isset($data['content']['description'])&&!empty($data['content']['description']))
            {
                $description = $dom->createElement('description',$data['content']['description']);
                
                $content->appendChild($description);
            }
            
            if (isset($data['content']['url'])&&!empty($data['content']['url']))
            {
                $url = $dom->createElement('submitted-url',$data['content']['url']);
                
                $content->appendChild($url);
            }
            
            if (isset($data['content']['image'])&&!empty($data['content']['image']))
            {
                $image = $dom->createElement('submitted-image-url',$data['content']['image']);
                
                $content->appendChild($image);
            }
            
            $share->appendChild($content);
        }
        
        $visibility = $dom->createElement('visibility');
        
        $code = $dom->createElement('code',$data['share']['code']);
        
        $visibility->appendChild($code);
        
        $share->appendChild($visibility);
        
        $dom->appendChild($share);
        
        return $dom->saveXML();
    }
    
}


?>