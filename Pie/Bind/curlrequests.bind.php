<?php

class CurlRequests extends Curl
{
    
    public function GetFeed($url)
    {
        
        $xml = self::GrabXML($url);
        
        return $xml;
        
    }
    
    public function GetJSON($url)
    {
        
        $json = self::GrabJson($url);
        
        return $json;
        
    }
    
    public function HttpPostJson($url,$data)
    {
        $json = self::PostJson($url,$data);
        
        return $json;
    }
    
    public function HttpPostXML($url,$data)
    {
        $json = self::PostXML($url,$data);
        
        return $json;
    }
    
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
