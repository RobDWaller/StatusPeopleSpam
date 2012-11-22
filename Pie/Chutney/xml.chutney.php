<?php

class XML
{
    
    public function XMLAPIOutput($code,$message,$data,$simple,$tag)
    {
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<response>';
        $xml .= '<code>'.$code.'</code>';
        $xml .= '<message>'.$message.'</message>';
        $xml .= '<data>';
        
        if (!$simple)
        {
            foreach ($data as $key => $obj)
            {
                
                if (is_array($obj))
                {
                    $xml .= '<'.$tag.'>';

                    foreach ($obj as $k => $o)
                    {
                        $xml .= '<row'.$k.'>'.$o.'</row'.$k.'>';
                    }

                    $xml .= '</'.$tag.'>';
                }
                else 
                {
                    $xml .= '<'.$key.'>'.$obj.'</'.$key.'>';
                }
            }
        }
        else
        {
            foreach ($data as $key => $obj)
            {
                $xml .= '<'.$key.'>'.$obj.'</'.$key.'>';
            }
        }
        
        $xml .= '</data>';
        $xml .= '</response>';
        
        return $xml;
        
    }
    
    public function XMLAPIError($code,$message)
    {
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<response>';
        $xml .= '<code>'.$code.'</code>';
        $xml .= '<message>'.$message.'</message>';
        $xml .= '</response>';
        
        return $xml;
        
    }
    
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
