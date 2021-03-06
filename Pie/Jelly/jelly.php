<?php

//PorkPie 0.0.1

//namespace PorkPie\Jelly;

use Services\Routes\Loader;

class Jelly
{
	protected $loader;
	protected $mod_rewrite;
	protected $errorschutney;

	function __construct()
	{
		
		// This kicks everything off, if you wish to initiate a new pork (model) on load add it here (Also see mix.php).
		$this->loader = new Loader();
		//Chutney
		$this->errorschutney = new Errors();
		$this->mod_rewrite = true;
		//This sets your default error handler
		
		$this->errorschutney->IsDebug = $this->loader->isTest();

		set_error_handler(array($this->errorschutney, "ErrorHandler"), 30709); 
	}	
	
	public function Bake()
	{
		
		// Start the route process by passing the Route method the requested URL.
		
		$this->Route($_SERVER['REQUEST_URI']);
		
	}
	
	protected function Route($route)
	{

		// Turn the request url into an array.
		
		$this->loader->setClassesAndVariables($route);

		// Turn route array into varriables
		
		if ($this->loader->isIndexRoute()) {

			$this->Index($this->loader->getVariables());
		
		}	
			
		$this->loader->setClassMethod($this->mod_rewrite);			
		
		if ($this->loader->validClass($this->loader->getClass())) {

			if (!$this->loader->hasMethod($this->loader->getMethod())) {
				$this->loader->launch($this->loader->getClass(),'index',$this->loader->getVariables());
			
			}
			
			if (!$this->loader->isPrivate($this->loader->getMethod())){
				
				$this->loader->launch($this->loader->getClass(),$this->loader->getMethod(),$this->loader->getVariables());
		
			}
				
			$this->loader->fail();

		}
        
        $this->loader->screenNamePage($this->loader->getClass());
                    
				
	}
	
	// This is your default controller, if someone comes to http://example.com they will see this.
	
	protected function Index($vars)
	{
		
		//require_once(__SITE_PATH.'/Pie/Jelly/user.jelly.php');
		$fakers = new Fakers();
		
		$fakers->Index($vars);
		
	}
	
}

?>