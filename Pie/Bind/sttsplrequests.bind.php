<?php

class SttsplRequests extends sttspl
{
    
    public function ConvertToShortURL($accountid, $text, $type, $network)
    {

            //echo $text;
        
            preg_match_all('/https?:\/\/[a-zA-Z0-9\/\_\-\.\?\=\%\&\~\(\)\#\!]{1,}/',$text,$matches);

            //Errors::DebugArray($matches);
            
            foreach ($matches as $urls)
            {	
                    foreach ($urls as $url)
                    {		
                            //echo $url;
                            
                            if (!$this->CheckForShortUrls($url))
                            {				
                                    $shorturl = $this->CreateSttsplUrl(0, $url, $type, $network);
                                    
                                    //Errors::PrintArray($shorturl);
                                    
                                    $text = preg_replace('|'.preg_quote($url).'|',$shorturl['url'],$text,1,$count);
                                    $ids[] = $shorturl['id'];
                                    
//                                    echo $newtext;
//                                    die();
                            }					
                    }
            }
           
            return array('text'=>$text,'urlids'=>$ids);

    }

    public function CreateSttsplUrl($accountid, $url, $type, $network = 0, $title = null)
    {

            $validation = new Validation();

            $hash = $validation->HashString($url);

            $url = $validation->SanitizeString($url);

            $date = time();

            $query = "INSERT INTO sttsp_urls (accountid,url,guid,title,type,network,created)
                                    VALUES (:accountid,:url,:guid,:title,:type,:network,:date)";

            $params = array('accountid'=>array($accountid,'INT',0),'url'=>array($url,'STR',255),'guid'=>array($hash,'STR',64),'title'=>array($title,'STR',255),'type'=>array($type,'INT',0),'network'=>array($network,'INT',0),'date'=>array($date,'INT',0));

            $returnid = $this->InsertRecord($query,$params);
            
            $id = $returnid;
            
            $id = $id + 1;
            $rand = rand(0,9);

            $num = $id.$rand;

            $returnurl = "http://sttsp.pl/".base_convert($num,10,36);

            return array('url'=>$returnurl,'id'=>$returnid);

    }

    protected function CheckForShortUrls($text)
    {

            $result = preg_match('/^http:\/\/sttsp.pl\/[a-zA-Z0-9]*$/',$text);

            return $result;

    }
    
}

?>