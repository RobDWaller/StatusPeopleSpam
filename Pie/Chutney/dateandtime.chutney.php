<?php

/* Date Time Chutney is a very small class but v useful. The Set Time method helps you display
Timezone based dates and times to your users. It will work perfectly with the timezone option
within the forms pork. */

//namespace PorkPie\Chutney;

class DateAndTime
{
	
	// To generate the time pass the format, in normal date() format, the time(), and the timezone to SetTime to generate the relevant date/time information. 
	
	public function SetTime($format,$timestamp,$timezone)
	{
		
		//date_default_timezone_set('Europe/London');
		$time = date('r',$timestamp);
		
		//echo $time;
		
		try
		{
			
			$dtzone = new DateTimeZone($timezone);
			
			$dtime = new DateTime($time);
			
			$dtime->setTimeZone($dtzone);
			
			$mytime = $dtime->format($format);
			
		}
		catch(exception $e)
		{
			
			die($e->getMessage());	
			
		}
		
		return $mytime;
		
	}
        
        public function GetOffset($timestamp,$zone)
        {
            
            $time = date('r',$timestamp);
            
            $ts = new DateTimeZone($zone);
            
            $date = new DateTime('2011-11-06 20:11');
            
            $date->setTimezone($ts);
            
            $offset = $date->getOffset();
            
            return $offset;
            
        }
        
        public function GetOffsetHours($timestamp,$zone)
        {
            
            $time = date('r',$timestamp);
            
            $ts = new DateTimeZone($zone);
            
            $date = new DateTime('2011-11-06 20:11');
            
            $date->setTimezone($ts);
            
            $offset = $date->getOffset();
            
            $offsethours = $offset/3600;
            
            return $offsethours;
            
        }
        
        public function CreateSQLOffset($hours)
        {
            
            if ($hours >= 0)
            {
                $sqloffset = str_pad($hours,3,'+0',STR_PAD_LEFT);
            }
            elseif ($hours < 0 && $hours >= -9)
            {
                $sqloffset = str_replace('-', '-0', $hours);
            }
            elseif ($hours < -9)
            {
                $sqloffset = str_pad($hours,3,'-',STR_PAD_LEFT);
            }
            
            $sqloffset = $sqloffset.':00';
            
            return $sqloffset;
            
        }
	
}

?>