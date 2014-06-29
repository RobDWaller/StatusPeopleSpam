<?php

class KredRequests extends Curl
{
    
    public function GetKredScore($username)
    {
        $url = 'http://api.kred.com/kredscore?app_id='.KRED_APP_ID.'&app_key='.KRED_KEY.'&source=twitter&term='.$username;
        
        $kred = self::GrabJson($url);
        
        return $kred;
    }
    
}

?>