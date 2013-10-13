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
		
            return intval($id);
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
        
		public function LanguageList()
		{
			$isoLangs = json_decode('{
				"ab":{
					"name":"Abkhaz",
					"nativeName":"аҧсуа"
				},
				"aa":{
					"name":"Afar",
					"nativeName":"Afaraf"
				},
				"af":{
					"name":"Afrikaans",
					"nativeName":"Afrikaans"
				},
				"ak":{
					"name":"Akan",
					"nativeName":"Akan"
				},
				"sq":{
					"name":"Albanian",
					"nativeName":"Shqip"
				},
				"am":{
					"name":"Amharic",
					"nativeName":"አማርኛ"
				},
				"ar":{
					"name":"Arabic",
					"nativeName":"العربية"
				},
				"an":{
					"name":"Aragonese",
					"nativeName":"Aragonés"
				},
				"hy":{
					"name":"Armenian",
					"nativeName":"Հայերեն"
				},
				"as":{
					"name":"Assamese",
					"nativeName":"অসমীয়া"
				},
				"av":{
					"name":"Avaric",
					"nativeName":"авар мацӀ, магӀарул мацӀ"
				},
				"ae":{
					"name":"Avestan",
					"nativeName":"avesta"
				},
				"ay":{
					"name":"Aymara",
					"nativeName":"aymar aru"
				},
				"az":{
					"name":"Azerbaijani",
					"nativeName":"azərbaycan dili"
				},
				"bm":{
					"name":"Bambara",
					"nativeName":"bamanankan"
				},
				"ba":{
					"name":"Bashkir",
					"nativeName":"башҡорт теле"
				},
				"eu":{
					"name":"Basque",
					"nativeName":"euskara, euskera"
				},
				"be":{
					"name":"Belarusian",
					"nativeName":"Беларуская"
				},
				"bn":{
					"name":"Bengali",
					"nativeName":"বাংলা"
				},
				"bh":{
					"name":"Bihari",
					"nativeName":"भोजपुरी"
				},
				"bi":{
					"name":"Bislama",
					"nativeName":"Bislama"
				},
				"bs":{
					"name":"Bosnian",
					"nativeName":"bosanski jezik"
				},
				"br":{
					"name":"Breton",
					"nativeName":"brezhoneg"
				},
				"bg":{
					"name":"Bulgarian",
					"nativeName":"български език"
				},
				"my":{
					"name":"Burmese",
					"nativeName":"ဗမာစာ"
				},
				"ca":{
					"name":"Catalan; Valencian",
					"nativeName":"Català"
				},
				"ch":{
					"name":"Chamorro",
					"nativeName":"Chamoru"
				},
				"ce":{
					"name":"Chechen",
					"nativeName":"нохчийн мотт"
				},
				"ny":{
					"name":"Chichewa; Chewa; Nyanja",
					"nativeName":"chiCheŵa, chinyanja"
				},
				"zh":{
					"name":"Chinese",
					"nativeName":"中文 (Zhōngwén), 汉语, 漢語"
				},
				"cv":{
					"name":"Chuvash",
					"nativeName":"чӑваш чӗлхи"
				},
				"kw":{
					"name":"Cornish",
					"nativeName":"Kernewek"
				},
				"co":{
					"name":"Corsican",
					"nativeName":"corsu, lingua corsa"
				},
				"cr":{
					"name":"Cree",
					"nativeName":"ᓀᐦᐃᔭᐍᐏᐣ"
				},
				"hr":{
					"name":"Croatian",
					"nativeName":"hrvatski"
				},
				"cs":{
					"name":"Czech",
					"nativeName":"česky, čeština"
				},
				"da":{
					"name":"Danish",
					"nativeName":"dansk"
				},
				"dv":{
					"name":"Divehi; Dhivehi; Maldivian;",
					"nativeName":"ދިވެހި"
				},
				"nl":{
					"name":"Dutch",
					"nativeName":"Nederlands, Vlaams"
				},
				"en":{
					"name":"English",
					"nativeName":"English"
				},
				"eo":{
					"name":"Esperanto",
					"nativeName":"Esperanto"
				},
				"et":{
					"name":"Estonian",
					"nativeName":"eesti, eesti keel"
				},
				"ee":{
					"name":"Ewe",
					"nativeName":"Eʋegbe"
				},
				"fo":{
					"name":"Faroese",
					"nativeName":"føroyskt"
				},
				"fj":{
					"name":"Fijian",
					"nativeName":"vosa Vakaviti"
				},
				"fi":{
					"name":"Finnish",
					"nativeName":"suomi, suomen kieli"
				},
				"fr":{
					"name":"French",
					"nativeName":"français, langue française"
				},
				"ff":{
					"name":"Fula; Fulah; Pulaar; Pular",
					"nativeName":"Fulfulde, Pulaar, Pular"
				},
				"gl":{
					"name":"Galician",
					"nativeName":"Galego"
				},
				"ka":{
					"name":"Georgian",
					"nativeName":"ქართული"
				},
				"de":{
					"name":"German",
					"nativeName":"Deutsch"
				},
				"el":{
					"name":"Greek, Modern",
					"nativeName":"Ελληνικά"
				},
				"gn":{
					"name":"Guaraní",
					"nativeName":"Avañeẽ"
				},
				"gu":{
					"name":"Gujarati",
					"nativeName":"ગુજરાતી"
				},
				"ht":{
					"name":"Haitian; Haitian Creole",
					"nativeName":"Kreyòl ayisyen"
				},
				"ha":{
					"name":"Hausa",
					"nativeName":"Hausa, هَوُسَ"
				},
				"he":{
					"name":"Hebrew (modern)",
					"nativeName":"עברית"
				},
				"hz":{
					"name":"Herero",
					"nativeName":"Otjiherero"
				},
				"hi":{
					"name":"Hindi",
					"nativeName":"हिन्दी, हिंदी"
				},
				"ho":{
					"name":"Hiri Motu",
					"nativeName":"Hiri Motu"
				},
				"hu":{
					"name":"Hungarian",
					"nativeName":"Magyar"
				},
				"ia":{
					"name":"Interlingua",
					"nativeName":"Interlingua"
				},
				"id":{
					"name":"Indonesian",
					"nativeName":"Bahasa Indonesia"
				},
				"ie":{
					"name":"Interlingue",
					"nativeName":"Originally called Occidental; then Interlingue after WWII"
				},
				"ga":{
					"name":"Irish",
					"nativeName":"Gaeilge"
				},
				"ig":{
					"name":"Igbo",
					"nativeName":"Asụsụ Igbo"
				},
				"ik":{
					"name":"Inupiaq",
					"nativeName":"Iñupiaq, Iñupiatun"
				},
				"io":{
					"name":"Ido",
					"nativeName":"Ido"
				},
				"is":{
					"name":"Icelandic",
					"nativeName":"Íslenska"
				},
				"it":{
					"name":"Italian",
					"nativeName":"Italiano"
				},
				"iu":{
					"name":"Inuktitut",
					"nativeName":"ᐃᓄᒃᑎᑐᑦ"
				},
				"ja":{
					"name":"Japanese",
					"nativeName":"日本語 (にほんご／にっぽんご)"
				},
				"jv":{
					"name":"Javanese",
					"nativeName":"basa Jawa"
				},
				"kl":{
					"name":"Kalaallisut, Greenlandic",
					"nativeName":"kalaallisut, kalaallit oqaasii"
				},
				"kn":{
					"name":"Kannada",
					"nativeName":"ಕನ್ನಡ"
				},
				"kr":{
					"name":"Kanuri",
					"nativeName":"Kanuri"
				},
				"ks":{
					"name":"Kashmiri",
					"nativeName":"कश्मीरी, كشميري‎"
				},
				"kk":{
					"name":"Kazakh",
					"nativeName":"Қазақ тілі"
				},
				"km":{
					"name":"Khmer",
					"nativeName":"ភាសាខ្មែរ"
				},
				"ki":{
					"name":"Kikuyu, Gikuyu",
					"nativeName":"Gĩkũyũ"
				},
				"rw":{
					"name":"Kinyarwanda",
					"nativeName":"Ikinyarwanda"
				},
				"ky":{
					"name":"Kirghiz, Kyrgyz",
					"nativeName":"кыргыз тили"
				},
				"kv":{
					"name":"Komi",
					"nativeName":"коми кыв"
				},
				"kg":{
					"name":"Kongo",
					"nativeName":"KiKongo"
				},
				"ko":{
					"name":"Korean",
					"nativeName":"한국어 (韓國語), 조선말 (朝鮮語)"
				},
				"ku":{
					"name":"Kurdish",
					"nativeName":"Kurdî, كوردی‎"
				},
				"kj":{
					"name":"Kwanyama, Kuanyama",
					"nativeName":"Kuanyama"
				},
				"la":{
					"name":"Latin",
					"nativeName":"latine, lingua latina"
				},
				"lb":{
					"name":"Luxembourgish, Letzeburgesch",
					"nativeName":"Lëtzebuergesch"
				},
				"lg":{
					"name":"Luganda",
					"nativeName":"Luganda"
				},
				"li":{
					"name":"Limburgish, Limburgan, Limburger",
					"nativeName":"Limburgs"
				},
				"ln":{
					"name":"Lingala",
					"nativeName":"Lingála"
				},
				"lo":{
					"name":"Lao",
					"nativeName":"ພາສາລາວ"
				},
				"lt":{
					"name":"Lithuanian",
					"nativeName":"lietuvių kalba"
				},
				"lu":{
					"name":"Luba-Katanga",
					"nativeName":""
				},
				"lv":{
					"name":"Latvian",
					"nativeName":"latviešu valoda"
				},
				"gv":{
					"name":"Manx",
					"nativeName":"Gaelg, Gailck"
				},
				"mk":{
					"name":"Macedonian",
					"nativeName":"македонски јазик"
				},
				"mg":{
					"name":"Malagasy",
					"nativeName":"Malagasy fiteny"
				},
				"ms":{
					"name":"Malay",
					"nativeName":"bahasa Melayu, بهاس ملايو‎"
				},
				"ml":{
					"name":"Malayalam",
					"nativeName":"മലയാളം"
				},
				"mt":{
					"name":"Maltese",
					"nativeName":"Malti"
				},
				"mi":{
					"name":"Māori",
					"nativeName":"te reo Māori"
				},
				"mr":{
					"name":"Marathi (Marāṭhī)",
					"nativeName":"मराठी"
				},
				"mh":{
					"name":"Marshallese",
					"nativeName":"Kajin M̧ajeļ"
				},
				"mn":{
					"name":"Mongolian",
					"nativeName":"монгол"
				},
				"na":{
					"name":"Nauru",
					"nativeName":"Ekakairũ Naoero"
				},
				"nv":{
					"name":"Navajo, Navaho",
					"nativeName":"Diné bizaad, Dinékʼehǰí"
				},
				"nb":{
					"name":"Norwegian Bokmål",
					"nativeName":"Norsk bokmål"
				},
				"nd":{
					"name":"North Ndebele",
					"nativeName":"isiNdebele"
				},
				"ne":{
					"name":"Nepali",
					"nativeName":"नेपाली"
				},
				"ng":{
					"name":"Ndonga",
					"nativeName":"Owambo"
				},
				"nn":{
					"name":"Norwegian Nynorsk",
					"nativeName":"Norsk nynorsk"
				},
				"no":{
					"name":"Norwegian",
					"nativeName":"Norsk"
				},
				"ii":{
					"name":"Nuosu",
					"nativeName":"ꆈꌠ꒿ Nuosuhxop"
				},
				"nr":{
					"name":"South Ndebele",
					"nativeName":"isiNdebele"
				},
				"oc":{
					"name":"Occitan",
					"nativeName":"Occitan"
				},
				"oj":{
					"name":"Ojibwe, Ojibwa",
					"nativeName":"ᐊᓂᔑᓈᐯᒧᐎᓐ"
				},
				"cu":{
					"name":"Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic",
					"nativeName":"ѩзыкъ словѣньскъ"
				},
				"om":{
					"name":"Oromo",
					"nativeName":"Afaan Oromoo"
				},
				"or":{
					"name":"Oriya",
					"nativeName":"ଓଡ଼ିଆ"
				},
				"os":{
					"name":"Ossetian, Ossetic",
					"nativeName":"ирон æвзаг"
				},
				"pa":{
					"name":"Panjabi",
					"nativeName":""
				},
				"pi":{
					"name":"Pāli",
					"nativeName":"पाऴि"
				},
				"fa":{
					"name":"Persian",
					"nativeName":"فارسی"
				},
				"pl":{
					"name":"Polish",
					"nativeName":"polski"
				},
				"ps":{
					"name":"Pashto, Pushto",
					"nativeName":"پښتو"
				},
				"pt":{
					"name":"Portuguese",
					"nativeName":"Português"
				},
				"qu":{
					"name":"Quechua",
					"nativeName":"Runa Simi, Kichwa"
				},
				"rm":{
					"name":"Romansh",
					"nativeName":"rumantsch grischun"
				},
				"rn":{
					"name":"Kirundi",
					"nativeName":"kiRundi"
				},
				"ro":{
					"name":"Romanian, Moldavian, Moldovan",
					"nativeName":"română"
				},
				"ru":{
					"name":"Russian",
					"nativeName":"русский язык"
				},
				"sa":{
					"name":"Sanskrit (Saṁskṛta)",
					"nativeName":"संस्कृतम्"
				},
				"sc":{
					"name":"Sardinian",
					"nativeName":"sardu"
				},
				"sd":{
					"name":"Sindhi",
					"nativeName":"सिन्धी, سنڌي، سندھی‎"
				},
				"se":{
					"name":"Northern Sami",
					"nativeName":"Davvisámegiella"
				},
				"sm":{
					"name":"Samoan",
					"nativeName":"gagana faa Samoa"
				},
				"sg":{
					"name":"Sango",
					"nativeName":"yângâ tî sängö"
				},
				"sr":{
					"name":"Serbian",
					"nativeName":"српски језик"
				},
				"gd":{
					"name":"Scottish Gaelic; Gaelic",
					"nativeName":"Gàidhlig"
				},
				"sn":{
					"name":"Shona",
					"nativeName":"chiShona"
				},
				"si":{
					"name":"Sinhala, Sinhalese",
					"nativeName":"සිංහල"
				},
				"sk":{
					"name":"Slovak",
					"nativeName":"slovenčina"
				},
				"sl":{
					"name":"Slovene",
					"nativeName":"slovenščina"
				},
				"so":{
					"name":"Somali",
					"nativeName":"Soomaaliga, af Soomaali"
				},
				"st":{
					"name":"Southern Sotho",
					"nativeName":"Sesotho"
				},
				"es":{
					"name":"Spanish; Castilian",
					"nativeName":"español, castellano"
				},
				"su":{
					"name":"Sundanese",
					"nativeName":"Basa Sunda"
				},
				"sw":{
					"name":"Swahili",
					"nativeName":"Kiswahili"
				},
				"ss":{
					"name":"Swati",
					"nativeName":"SiSwati"
				},
				"sv":{
					"name":"Swedish",
					"nativeName":"svenska"
				},
				"ta":{
					"name":"Tamil",
					"nativeName":"தமிழ்"
				},
				"te":{
					"name":"Telugu",
					"nativeName":"తెలుగు"
				},
				"tg":{
					"name":"Tajik",
					"nativeName":"тоҷикӣ, toğikī, تاجیکی‎"
				},
				"th":{
					"name":"Thai",
					"nativeName":"ไทย"
				},
				"ti":{
					"name":"Tigrinya",
					"nativeName":"ትግርኛ"
				},
				"bo":{
					"name":"Tibetan Standard, Tibetan, Central",
					"nativeName":"བོད་ཡིག"
				},
				"tk":{
					"name":"Turkmen",
					"nativeName":"Türkmen, Түркмен"
				},
				"tl":{
					"name":"Tagalog",
					"nativeName":"Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔"
				},
				"tn":{
					"name":"Tswana",
					"nativeName":"Setswana"
				},
				"to":{
					"name":"Tonga (Tonga Islands)",
					"nativeName":"faka Tonga"
				},
				"tr":{
					"name":"Turkish",
					"nativeName":"Türkçe"
				},
				"ts":{
					"name":"Tsonga",
					"nativeName":"Xitsonga"
				},
				"tt":{
					"name":"Tatar",
					"nativeName":"татарча, tatarça, تاتارچا‎"
				},
				"tw":{
					"name":"Twi",
					"nativeName":"Twi"
				},
				"ty":{
					"name":"Tahitian",
					"nativeName":"Reo Tahiti"
				},
				"ug":{
					"name":"Uighur, Uyghur",
					"nativeName":"Uyƣurqə, ئۇيغۇرچە‎"
				},
				"uk":{
					"name":"Ukrainian",
					"nativeName":"українська"
				},
				"ur":{
					"name":"Urdu",
					"nativeName":"اردو"
				},
				"uz":{
					"name":"Uzbek",
					"nativeName":"zbek, Ўзбек, أۇزبېك‎"
				},
				"ve":{
					"name":"Venda",
					"nativeName":"Tshivenḓa"
				},
				"vi":{
					"name":"Vietnamese",
					"nativeName":"Tiếng Việt"
				},
				"vo":{
					"name":"Volapük",
					"nativeName":"Volapük"
				},
				"wa":{
					"name":"Walloon",
					"nativeName":"Walon"
				},
				"cy":{
					"name":"Welsh",
					"nativeName":"Cymraeg"
				},
				"wo":{
					"name":"Wolof",
					"nativeName":"Wollof"
				},
				"fy":{
					"name":"Western Frisian",
					"nativeName":"Frysk"
				},
				"xh":{
					"name":"Xhosa",
					"nativeName":"isiXhosa"
				},
				"yi":{
					"name":"Yiddish",
					"nativeName":"ייִדיש"
				},
				"yo":{
					"name":"Yoruba",
					"nativeName":"Yorùbá"
				},
				"za":{
					"name":"Zhuang, Chuang",
					"nativeName":"Saɯ cueŋƅ, Saw cuengh"
				}
			}');
			
			return $isoLangs;
		}
}

?>