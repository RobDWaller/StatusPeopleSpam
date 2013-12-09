<?php

class JSON
{
    public function JSONAPIOutput($code,$message,$data)
    {
        
        $json['code'] = $code;
        $json['message'] = $message;
        $json['data'] = $data;
        
        $output = json_encode($json);
        
        return $output;
        
    }
    
    public function JSONAPIError($code,$message,$data=0)
    {
        $json['code'] = $code;
        $json['message'] = $message;
		$json['data'] = $data;
        
        $output = json_encode($json);
        
        return $output;
    }
    
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
