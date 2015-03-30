<?php namespace Services\Routes;

class Loader
{
	protected $routes;
	protected $classes;
	protected $variables;
	protected $class;
	protected $method;

	public function __construct()
	{
		$this->routes = new \Route();
		$this->glaze = new \Glaze();
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
			$this->class = $clss_1;
			$this->method = $clss_2;	
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

	public function launch($class,$method,$vars)
	{
		$newClass = new $class;

		if ($this->routes->CheckForMethod($newClass,$method)) {

			$newClass->$method($vars);

		}
		else {
			$this->fail();
		}
	}

	public function isPrivate($method)
	{
		return $this->routes->IsPrivateFunction($method);
	}

	public function screenNamePage($class)
	{
		$class = str_replace('@','',$class);

		$count = \APIRequests::CheckForScreenNameScore($class);

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
        $data['homelink'] = $this->routes->HREF('/Fakers',$this->mod_rewrite);
        $data['message'] = \Build::PageMessage('alert',array('This page does not exist.'));
        $data['menu'] = \Fakers::_BuildMenu();
		$data['logout'] = 2;
		$this->glaze->view('error.php',$data);
	}

}