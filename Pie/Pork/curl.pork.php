<?php

// Simple cURL model for making http requests and returning json or xml based objects

class Curl
{
	
	public function GrabJson($url)
	{
	
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL,$url);
		
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		
		// This option is only needed when on IIS.
		//curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
		
		$json = curl_exec($ch);
		
		$output = json_decode($json);
                
                $info = curl_getinfo($ch);
                
//                print_r($info);
//                print_r(curl_errno($ch));
		
		curl_close($ch);
		
		//print_r($output);
		
		return $output;
	
	}
        
        public function PostJson($url,$data)
	{
	
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,true);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
                
		// This option is only needed when on IIS.
		//curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
		
		$json = curl_exec($ch);
		$info = curl_getinfo($ch);
                
//                print_r($info);
//                print_r(curl_errno($ch));
                
		$output = json_decode($json);
		
		curl_close($ch);
		
		//print_r($output);
		
		return $output;
	
	}
	
	public function GetXMLString($url)
	{
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL,$url);
		
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
		
		curl_setopt($ch,CURLOPT_FRESH_CONNECT,true);
		
		libxml_use_internal_errors(true);
		
		$xml = curl_exec($ch);
		
		curl_close($ch);
		
		return $xml;
	}
	
	public function GrabXML($url)
	{
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL,$url);
		
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
                
                curl_setopt($ch,CURLOPT_FRESH_CONNECT,true);
		
		// This option is only needed when on IIS.
		//curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		
                libxml_use_internal_errors(true);
                
		$xml = curl_exec($ch);
                    
                $xml = str_replace('""', '"', $xml);
                
                try
                {
                    $output = new SimpleXMLElement($xml);
                    
                    if (!$output)
                    {
//                        foreach(libxml_get_errors() as $error) {
//                            echo $error->message.'<br/>';
//                        }
                    }
                }
                catch (Exception $e)
                {
                    $output = $e->getMessage();
                }
                
		curl_close($ch);
		
		//print_r($output);
		
		return $output;
		
	}
	
        public function PostXML($url,$data)
	{
		
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_URL,$url);
		
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,true);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
		
		// This option is only needed when on IIS.
		//curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		
		$xml = curl_exec($ch);
                    
                $xml = str_replace('""', '"', $xml);
                
//                echo $xml;
//                die();
                
                try
                {
                    $output = new SimpleXMLElement($xml);
                }
                catch (Exception $e)
                {
                    $output = $e->getMessage();
                }
                
		curl_close($ch);
		
//		print_r($output);
//		die();
                
		return $output;
		
	}
	
}

?>