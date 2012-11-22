<?php

/* Validation Chutney helps you to validate content before it is passed to your database. It also has a number of hashing methods. */

//namespace PorkPie\Chutney;

class Validation
{
	
	// SaltNumber helps generate your database salt for your application. It must be edited each time you build an apllication. 
	
	//protected $SaltNumber = 541257896335411; 
	protected $SaltNumber = 5.41257896335E+14;	
		
	protected $MaxFileSize = 5000000;
	protected $MaxImageWidth = 2000;
	protected $MaxImageHeight = 2000;	
		
	public function ValidateIntegers($ints)
	{
		
		$valid = true;
		$message = array();
	
		foreach ($ints as $key => $obj)
		{
		
			if (!preg_match('/^[0-9]{1,255}$/', $obj))
			{
				$valid = false;
				$message[] = "You did not submit a valid $key.";			
			}
		
		}	
		
		return array($valid,$message);
		
	}
	
	public function ValidateInteger($int,$name)
	{
		
		$valid = true;
		
		if (!preg_match('/^[0-9]{1,255}$/', $int))
		{
			$valid = false;
			$message = "You did not submit a valid $name.";			
		}
		
		return array($valid,$message);
		
	}
	
	public function ValidateStrings($strings)
	{
		
		$valid = true;
		$message = array();
	
		foreach ($strings as $key => $obj)
		{	
			if (empty($obj) || strlen($obj) > 255)
			{
				
				$valid = false;
				$message[] = 'You did not fill in a valid '.$key.'.';
				
			}
		}	
		
		return array($valid,$message);
		
	}
	
	public function ValidateString($string,$name)
	{
		
		$valid = true;
		
		if (empty($string) || strlen($string) > 255)
		{
			$valid = false;
			$message = "You did not submit a valid $name.";			
		}
		
		return array($valid,$message);
		
	}
	
	public function ValidateLongString($string,$name)
	{
		
		$valid = true;
		
		if (empty($string))
		{
			$valid = false;
			$message = "You did not submit a valid $name.";			
		}
		
		return array($valid,$message);
		
	}
	
	//Updates
	
	public function ValidatePassword($password)
	{
		
		$valid = true;
		$message = '';
		
		if (!preg_match('/^[\w-@#]{8,255}$/', $password))
		{
			$valid = false;
			$message = 'Your password must be at least 8 characters in length and only contain the following characters a-z A-Z 0-9 _-@#';
		}
		
		return array($valid,$message);
		
	}
	
	public function ValidateEmail($email)
	{
	
		$valid = true;
		$message = '';
	
		if (!preg_match('/^[a-zA-Z0-9\+._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z.]{2,5}$/', $email) || strlen($email) > 255)
		{
			
			$valid = false;
			$message = 'You did not submit a valid Email Address.';
			
		}
		
		return array($valid,$message);
		
	}
	
	public function ValidateWebsite($website)
	{
		
		$valid = true;
		$message = '';
		
		if (!preg_match('/^http:\/\/[\w.\/-]+\.[a-zA-Z.]{2,5}$/', $email) || strlen($website) > 500)
		{
			
			$valid = false;
			$message = 'You did not submit a valid website.';
			
		}
		
		return array($valid,$message);
		
	}
	
	public function ValidateUrl($url)	
	{	
		$valid = true;
		$message = '';
		
		if (!preg_match('/^https?:\/\/.{1,70}\.[a-zA-Z.]{2,5}.{0,1000}$/',$url))
		{
			$valid = false;
			$message = 'You did not enter a valid URL.';		
		}
		
		return array($valid,$message);	
	}
	
	public function ValidateDate($date, $regex, $format)
	{
		
		$valid = true;
		$message = '';
		
		if(!preg_match($regex,$date))
		{
			
			$valid = false;
			$message = 'Please enter a valid date matching this format '.$format.'.';
			
		}
		
		return array($valid,$message);
		
	}
	
	//new
	
	public function ValidateTime($time)
	{
		
		$valid = true;
		$message = '';
		
		$timevars = explode(':',$time);
		
		if(!preg_match('/^[0-9]{2}:[0-9]{2}$/',$time))
		{
			
			$valid = false;
			$message = 'Please enter a valid time matching this format HH:MM 1.';
			
		}
		elseif($timevars[0] > 24 || $timevars[1] > 59)
		{
			
			$valid = false;
			$message = 'Please enter a valid time matching this format HH:MM 2.';
			
		}
		
		return array($valid,$message);
		
	}
	
	//new
	public function ValidateFileUpload($name)
	{
		
		$valid = true;
		$message = array();
		
                if ($_FILES[$name]["tmp_name"])
                {
                    list($width, $height) = getimagesize($_FILES[$name]["tmp_name"]);
                }
                else
                {
                    $valid = false;
                    $message[] = 'You must select a file to upload.';
                }
                
		if ( ($_FILES[$name]["type"] != "image/gif") && ($_FILES[$name]["type"] != "image/jpeg") && ($_FILES[$name]["type"] != "image/pjpeg") && ($_FILES[$name]["type"] != "image/png") && ($_FILES[$name]["type"] != "application/pdf") )
		{
			$valid = false;
			$message[] = 'File was not valid, must be gif, jpeg, png or pdf.';	
		}
		if ($_FILES[$name]["size"] > $this->MaxFileSize)
		{
			$valid = false;
			$message[] =  'Your file can be no larger than '.$this->MaxFileSize.'kb';
		}
		if ($_FILES[$name]["error"] > 0)
		{
			$valid = false;
			$message[] = 'Upload Error: '.$_FILES[$name]["error"].'.';
		}
		if (file_exists("/Pie/Crust/Uploads/".$_FILES[$name]["name"]))
		{
			$valid = false;
			$message[] = 'This file already exists '.$_FILES[$name]["name"];
		}
		if (!preg_match("/^[a-zA-Z0-9.-_]+$/", $_FILES[$name]["name"]))
		{
			$valid = false;
			$message[] = 'The file name must not contain any whitespace.';
		}
		if ($_FILES[$name]["type"] != "application/pdf" && ($width > $this->MaxImageWidth || $height > $this->MaxImageHeight))
		{
			$valid = false;
			$message[] = 'The image must be no bigger than '.$this->MaxImageWidth.'px wide and '.$this->MaxImageHeight.'px high.';				
		}
		
		return array($valid,$message);
		
	}
        
        public function ValidateHexCode($code,$name)
        {
            
            $valid = true;
            $message = array();
            
            if (!preg_match('|^#[a-zA-Z0-9]{6}|',$code))
            {
                $valid = false;
                $message = $name.' is not a valid hex code.';
            }
            
            return array($valid,$message);
            
        }
	
	public function CheckPasswordMatch($pass1, $pass2)
	{
		
		$valid = true;
		$message = '';
		
		if ($pass1 !== $pass2)
		{
			$valid = false;
			$message = "Your passwords do not match.";
		}
		
		return array($valid,$message);
		
	}
        
        public function IsValid($data)
        {

            $isvalid = true;

            foreach ($data as $d)
            {
                if (!$d[0])
                {
                    $isvalid = false;
                    $messages[] = $d[1];
                }
            }

            return array($isvalid,$messages);
        }

        public function BuildErrorMessages($errors)
        {
            $output = '<ul id="errors">';

            foreach ($errors as $error)
            {
                $output .= "<li>$error</li>";
            }

            $output .= '</ul>';

            return $output;
        }
	
	public function SanitizeStrings($strings)
	{

		$sanitized = array();

		foreach ($strings as $key => $obj)
		{
		
			$sanitized[$key] =  filter_var(addslashes($obj),FILTER_SANITIZE_STRING);
			
		}
		
		return $sanitized;
		
	}
	
	public function SanitizeString($string)
	{
			
		$sanitized =  filter_var(addslashes($string),FILTER_SANITIZE_STRING);
			
		return $sanitized;
		
	}
	
	public function SanitizeFreeText($text)
	{
	
		$text = strip_tags($text);
		$text = addslashes($text);
		
		return $text;
		
	}
	
	public function ConvertUrlToLink($text,$target = null)
	{
		
                $paterns[] = '/http:\/\/([a-zA-Z0-9\/\_\-\.]*)/';
                $paterns[] = '/https:\/\/([a-zA-Z0-9\/\_\-\.]*)/';
            
		if ($target != null)
		{
			$text = preg_replace($paterns,'<a href="http://$1" target="'.$target.'" class="createdlink">$1</a>',$text);
		}
		else
		{
			$text = preg_replace($paterns, '<a href="http://$1" class="createdlink">$1</a>',$text);
		}
		
		return $text;
	
	}
	
	// This HashString method is only suitable for small websites that will not present a data trophy target to hackers.
	
	public function HashString($string)
	{
		
		$hash = hash('sha256',$string.self::GetSalt());
		return $hash;
		
	}
	
	// You should change $num within GetSalt every time you build a new application.
	
	private function GetSalt()
	{
	
		$salt = base_convert($this->SaltNumber,10,36);
		return $salt;
		
	}
	
	public function UrlHash($hash)
	{
		
		$rand = rand(0,1000);
		$time = time();
		
		$str1 = substr(md5(base_convert($rand.$time,10,32)),2,8);
		$str2 = substr(md5(base_convert($rand.$time,10,32)),15,10);
		
		$urlhash = $str1.$hash.$str2;
		
		return $urlhash;	
		
	}
	
	public function UndoUrlHash($hash)
	{
	
		$undo = substr($hash,8,64);	
		
		return $undo;
		
	}
	
	public function ReplaceWhiteSpace($string,$replace = '_')
	{
	
		$output = preg_replace("/\s+/",$replace,$string);
		
		return $output;	
		
	}
	
	public function StripNonAlphanumeric($string)
	{
		$output = preg_replace("/[^a-zA-Z0-9_]/","",$string);
		
		return $output;
	}
        
        public function CheckLength($text,$length)
        {
            
            $result = true;
            
            $stringlength = strlen($text);
            
            if ($stringlength > $length)
            {
                
                $result = false;
                
            }
           
            return $result;
            
        }
	
	public function TrimWords($string)
	{
		
                $string = preg_replace("/ [ \t\n\r]+$/", "", $string);
                $string = preg_replace("/ [a-zA-Z0-9-]*$/", "...", $string);
                $string = preg_replace("/ \.\.\.\.$/", "...", $string);
                
		return $string;	
                
	}
        
        public function ObscureNumber($number)
        {
            
            $obs = $number + 5;
            $rand = rand(100000,999999);

            $num = $obs.$rand;
            
            $base = base_convert($num,10,36);
            
            return $base;
            
        }
        
        public function UnObscureNumber($base)
        {
            $obscureid = base_convert($base,36,10);
		
            $removelastint = substr($obscureid,0,-6);
		
            $id = $removelastint -5;
		
            return $id;
        }
        
        public function ProcessDateMetrics($metrics,$limit,$offset,$format,$outputformat,$period)
        {
            
            $i = 0;
            $c = 0;
            
            while($i < $limit)
            {
                
                $dateexists = false;
                
                $time = strtotime('-'.$i.' '.$period);
            
                $date = date($format,$time+$offset);
                
                foreach ($metrics as $metric)
                {
                    
                    //echo $metric['ClicksDate'].' = '.$date.'<br/>';
                    
                    if ($metric['date'] == $date)
                    {
                        $dateexists = true;
                    }
                }
                
                $date = date($outputformat,strtotime($date));
                
                if ($dateexists)
                {
                    $datesarray[] = array('date'=>$date,'count'=>$metrics[$c]['count']);
                    $c++;
                }
                else
                {
                    $datesarray[] = array('date'=>$date,'count'=>'0');
                }
                
                $i++;
            }
            
            return $datesarray;
            
        }
        
        public function ProcessHoursMetrics($metrics,$format,$outputformat)
        {
            $i = 0;
            $c = 0;
            
            $limit = 23;
            
            while($i <= $limit)
            {
                
                $dateexists = false;
                
                $time = strtotime('Today '.$i.':00');
            
                $date = date($format,$time);
                $outputdate = date($outputformat,$time);
                
                foreach ($metrics as $metric)
                {
                    
                    if ($metric['date'] == $date)
                    {
                        $dateexists = true;
                    }
                }
                
                if ($dateexists)
                {
                    $hoursarray[] = array('date'=>$outputdate,'count'=>$metrics[$c]['count']);
                    $c++;
                }
                else
                {
                    $hoursarray[] = array('date'=>$outputdate,'count'=>'0');
                }
                
                $i++;
            }
            
            return $hoursarray;
        }
        
        public function SanitizeDodgyEntities($str)
        {
            
            $entities = array('&rsquo;','&lsquo;');
            
            foreach ($entities as $entity)
            {
                $str = str_replace($entity, "'", $str);
            }
            
            return $str;
            
        }
        
        public function CreateAPIKey($accountid,$type)
        {
            
            $num = rand(1,1000000);
            
            $base = base_convert($num, 10, 36);
            
            $key = hash('sha256',$accountid.$type.$base);
            
            return $key;
            
        }
        
        public function OutputCode($code)
        {
            
            $code = str_replace('&', '&amp;', $code);
            $code = str_replace('\"', '&quot;', $code);
            $code = str_replace('<', '&lt;', $code);
            $code = str_replace('>', '&gt;', $code);
            
            return $code;            
        }
        
}

?>