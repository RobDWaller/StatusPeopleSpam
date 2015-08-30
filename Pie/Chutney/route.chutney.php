<?php

/* Route Chutney helps manage the routing within PorkPiePHP. It also helps you generate urls for use within the site.  

One important thing to remember /V/ seperates the class/method part of the PorkPiePHP URLs from the variables you
may wish to pass through. */

//namespace PorkPie\Chutney;

use Services\Routes\Loader;

class Route
{
	// protected $loader;

	// public function __construct()
	// {
	// 	$this->loader = new Loader;
	// }

	// BuildUrl will generate the relevant ULR for header functions and other similar uses.

	public function BuildUrl($url,$mod_rewrite = false)
	{

		$result = '';

		if ($mod_rewrite)
		{

			$result = $url;			
			
		}
		else
		{
		
			$result = '/index.php'.$url;	
			
		}

		return $result;
	
	}
	
	// HREF helps you build URLs for page links and other similar uses 
	
	public function HREF($url,$mod_rewrite = false)
	{

		$result = '';
		
		if ($mod_rewrite)
		{

			$result = 'https://'.$_SERVER['SERVER_NAME'].$url;			
			
		}
		else
		{
			
			$result = 'https://'.$_SERVER['SERVER_NAME'].'/index.php'.$url;	
			
		}
		
		$loader = new Loader;

		if ($loader->isTest())
		{
			$result = 'http://localhost'.$url;			
		}
		

		return $result;
		
	}
	
	// Pass an array of variables to CreateVariables to add them to the end of your URL
	
	public function CreateVariables($vars)
	{
	
		$url = '/V';
		
		foreach ($vars as $var)
		{
			
			$url .= '/'.$var;	
			
		}
		
		return $url;
		
	}
	
	// BuildClassesAndVars seperates your Class/Method from your URL Variables 
	
	public function BuildClassesAndVars($route)
	{
	
		$splitcv = explode('/V/',$route,2);	
		
		$classes = $this->BuildRouteArray($splitcv[0]);
		
		$vars = '';
		
		if (!empty($splitcv[1]))
		{
			$vars = $this->BuildRouteArray($splitcv[1]);
		}
		
		return array($classes,$vars);
		
	}
	
	// BuildRouteArray explodes the relevant Class and Method from your URL
	
	protected function BuildRouteArray($route)
	{
		
		$routesplit = explode('/',$route);
		
		$result = array();
		
		foreach($routesplit as $route)
		{
			
			$result[] = $route;
			
		}
		
		return $result;
		
	}
		
	// Check to see if a class exists within your Jelly.	
	
	public function CheckForClass($class)
	{
		
		return file_exists(__SITE_PATH.'/Pie/Jelly/'.strtolower($class).'.jelly.php')
				|| file_exists(__SITE_PATH.'/Pie/Controllers/'.$class.'.php'); 
		
	}

	// Check to see if the method exists within the class.

	public function CheckForMethod($class, $method)
	{
		
		$result = false;
		
		$result = method_exists($class,$method);
		
		return $result;	
		
	}
	
	public function CheckForGet($url)
	{
		
		$result = false;
		
		$matches = preg_match('/\?[a-zA-Z0-9_-]+=/',$url);
		
		if ($matches > 0)
		{
			$result = true;
		}
		
		return $result;
		
	}
	
	public function ExplodeGet($url)
	{
	
		$splitcv = explode('?',$url,2);	
		
		$classes = $this->BuildRouteArray($splitcv[0]);
		
		$vars = $this->BuildGetArray($splitcv[1]);
		
		return array($classes,$vars);
		
	}
	
	public function BuildGetArray($vars)
	{
		
		$vars = explode('&',$vars,10);	
		
		$returnvars = array();
		
		foreach ($vars as $var)
		{
		
			$split = explode('=',$var,2);	
			
			$returnvars[$split[0]] = $split[1]; 
			
		}
		
		return $returnvars;
		
	}
	
        public function IsPrivateFunction($method)
	{
            $result = false;
            
            if (preg_match('/^_/',$method,$matches))
            {
                $result = true;
            }
            
            return $result;
        }
}

?>