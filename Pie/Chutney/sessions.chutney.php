<?php

/* Sessions Chutney help you generate and manage Sessions and Cookies. */

//namespace PorkPie\Chutney;

class Sessions
{

	// Pass an array of sessions you wish to create. Format = array('Name'=>'Value')
	
	public function GenerateSessions($sessions)
	{
		
		foreach ($sessions as $key => $obj)
		{
			
			$_SESSION[$key] = $obj;
			
		}		
		
	}	
	
	// Destroy all sessions, only use this for logout systems and similar functions
	
	public static function DestroySessions()
	{
		
		session_unset();

//		if(isset($_COOKIE[session_name()]))
//		{
//			setcookie(session_name(),'',time()-10, '/');	
//		}

		session_destroy();	
		
	}
	
	// Pass an array of sessions you wish to unset. format = array('Name')
	
	public function UnsetSessions($sessions)
	{
		
		foreach ($sessions as $obj)
		{
		
			unset($_SESSION[$obj]);
			
		}
		
	}
	
	// Pass an array of cookies you wish to create. format = array('Name'=>'Value'). Also pass the time() you wish the cookies to last for.
	
	public function GenerateCookies($cookies,$time = 36000)
	{
		
		$duration = time() + $time;
		
		foreach ($cookies as $key => $obj)
		{
			setcookie($key,$obj,$duration,'/');
		}
		
	}
	
	// Pass an array of cookies you wish to destroy or unset. format = array('Name').
	
	public static function DestroyCookies($cookies)
	{
		
		foreach ($cookies as $key => $obj)
		{
			setcookie($key,'',time() - 10,'/');
		}
		
	}
        
        public function OverwriteCookies($cookies)
	{
		
		foreach ($cookies as $key => $obj)
		{
			$_COOKIE[$key] = $obj;
		}
		
	}
	
}
?>