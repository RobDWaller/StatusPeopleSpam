<?php

class CurlRequests extends Curl
{
    
    public function GetFeed($url)
    {
        
        $xml = $this->GrabXML($url);
        
        return $xml;
        
    }
    
    public function GetJSON($url)
    {
        
        $json = $this->GrabJson($url);
        
        return $json;
        
    }
    
    public function HttpPostJson($url,$data)
    {
        $json = $this->PostJson($url,$data);
        
        return $json;
    }
    
    public function HttpPostXML($url,$data)
    {
        $json = $this->PostXML($url,$data);
        
        return $json;
    }
    
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
