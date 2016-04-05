<?php namespace Services\Routes;

use Services\Config\Loader as Config;
use Fakers\Lists;

class Loader
{
	protected $routes;
	protected $glaze;
	protected $config;
	protected $api;
	protected $classes;
	protected $variables;
	protected $class;
	protected $method;
	protected $modRewrite = true;
	protected $lists;

	public function __construct()
	{
		$this->routes = new \Route();
		$this->glaze = new \Glaze();
		$this->config = new Config;
		$this->api = new \APIRequests;
		$this->build = new \Build();
		$this->lists = new Lists();
	}
	
	public function getClass()
	{
		return $this->class;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getVariables()
	{
		return $this->variables;
	}

	public function getClassesAndVariables($route)
	{
		return $this->routes->CheckForGet($route) 
				? $this->routes->ExplodeGet($route) 
				: $this->routes->BuildClassesAndVars($route);
	}
	
	public function setClassesAndVariables($route)
	{
		$obj = $this->getClassesAndVariables($route);

		$this->classes = $obj[0];
		$this->variables = $obj[1];
	}

	public function isIndexRoute()
	{
		extract($this->classes,EXTR_PREFIX_ALL,'clss');
		
		return ($clss_1 == 'index.php'|| !isset($clss_1) || empty($clss_1)) && (!isset($clss_2)||empty($clss_2));
	}

	public function setClassMethod($mod_rewrite)
	{
		extract($this->classes,EXTR_PREFIX_ALL,'clss');

		if ($mod_rewrite)
		{
			$this->class = isset($clss_1) ? $clss_1 : false;
			$this->method = isset($clss_2) ? $clss_2 : false;	
		}
		else
		{
			$this->class = $clss_2;
			$this->method = $clss_3;				
		}

	}

	public function validClass($class)
	{
		return $this->routes->CheckForClass($class);
	}

	public function hasMethod($method)
	{
		return isset($method)||!empty($method);
	}

	public function launch($class, $method, $vars)
	{
		if ($this->isNew($class)) {
			$class = 'Controllers\\'.$class;
		}

		$this->isBlackList($class);
		
		$newClass = new $class;

		if ($this->routes->CheckForMethod($newClass,$method)) {

			$newClass->$method($vars);

		}
				
		$this->fail();
	}

	public function isNew($class)
	{
		return file_exists(__SITE_PATH.'/Pie/Controllers/'.$class.'.php');
	}

	public function isPrivate($method)
	{
		return $this->routes->IsPrivateFunction($method);
	}

	protected function isBlackList($class)
	{
		if ($this->config->get('blacklist.' . $class)) {
			$this->fail();
		}
	}

	public function screenNamePage($class)
	{
		$class = str_replace('@', '', $class);

		$count = $this->api->CheckForScreenNameScore($class);

		if ($count > 0) {
			$fakers = new \Fakers();
			
			$fakers->_ShowUserData($class);
		}
		else {
			$this->fail();
		}
	}

	public function fail()
	{
		$data['title'] = 'Status People &mdash; Page Not Found.';
        $data['homelink'] = $this->routes->HREF('/Fakers', $this->modRewrite);
        $data['message'] = $this->build->PageMessage('alert',array('This page does not exist.'));
        $data['menu'] = $this->lists->menu();
		$data['logout'] = 2;
		$this->glaze->view('error.php', $data);
	}

	public function notLive()
	{
		return $this->isTest() || $this->isDown();
	}

	public function isTest()
	{
		return gethostname() == $this->config->get('app.test');
	}

	public function isDown()
	{
		return $this->config->get('app.down');
	}

}