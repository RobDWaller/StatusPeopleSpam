<?php

//PorkPie 0.0.1

//namespace PorkPie\Jelly;

class Jelly
{

	public $glaze;
        //Bind
        public $dbbind;
        public $sttsplbind;
        public $twitterbind;
        public $curlbind;
        public $kredbind;
        public $paymentbind;
		public $deepdivebind;
		public $mainbind;
		public $apibind;
        //Chutney        
        public $formschutney;
		public $buildchutney;
        public $seesionschutney;
        public $validationchutney;
        public $routechutney;
        public $emailchutney;
        public $errorschutney;
        public $datetimechutney;
        public $jsonchutney;
        public $xmlchutney;
        public $twitterchutney;
        public $facebookchutney;
        public $feedschutney;
		public $domchutney;
        
	// Normally you should have mod rewrite turned on, however if not set $mod_rewrite to false and your site will 
	// work with index.php/Class/Method urls.
	
	public $mod_rewrite = true;

	function __construct()
	{
		
		// This kicks everything off, if you wish to initiate a new pork (model) on load add it here (Also see mix.php).
		
		$this->glaze = new Glaze();
		//Bind
		$this->dbbind = new DBRequests();
		$this->sttsplbind = new SttsplRequests();
		$this->twitterbind = new TwitterRequests();
		$this->curlbind = new CurlRequests();
		$this->kredbind = new KredRequests();
		$this->paymentbind = new PaymentRequests();
		$this->deepdivebind = new DeepdiveRequests();
		$this->mainbind = new MainRequests();
		$this->apibind = new APIRequests();
		//Chutney
		$this->formschutney = new Forms();
		$this->buildchutney = new Build();
		$this->sessionschutney = new Sessions();
		$this->validationchutney = new Validation();
		$this->routechutney = new Route();
		$this->emailchutney = new Email();
		$this->errorschutney = new Errors();
		$this->datetimechutney = new DateAndTime();
		$this->jsonchutney = new JSON();
		$this->xmlchutney = new XML();
		$this->twitterchutney = new TwitterHelper();
		$this->facebookchutney = new FacebookHelper();
		$this->feedschutney = new FeedsHelper();
		$this->domchutney = new DomHelper();
		
                
		//This sets your default error handler
		
		set_error_handler(array($this->errorschutney,"ErrorHandler")); 
		
	}	
	
	public function Bake()
	{
		
		// Start the route process by passing the Route method the requested URL.
		
		$this->Route($_SERVER['REQUEST_URI']);
		
	}
	
	protected function Route($route)
	{
		
		// Turn the request url into an array.
		
		if ($this->routechutney->CheckForGet($route))
		{
			$classandvars = $this->routechutney->ExplodeGet($route);
		}
		else
		{
			$classandvars = $this->routechutney->BuildClassesAndVars($route);
		}
		
		$classes = $classandvars[0];
		
		$vars = $classandvars[1];
		
		// Turn route array into varriables
		
		extract($classes,EXTR_PREFIX_ALL,'clss');
		
		// Check if the home url has been requested.
		
		//print_r($clss_1);
		//die();			
		
		if (($clss_1 == 'index.php'|| !isset($clss_1) || empty($clss_1)) && (!isset($clss_2)||empty($clss_2)))
		{
		
			$this->Index($vars);
		
		}
		else
		{	
			
			if ($this->mod_rewrite)
			{
				$class = $clss_1;
				$method = $clss_2;	
			}
			else
			{
				$class = $clss_2;
				$method = $clss_3;				
			}			
			

                        $validclass = $this->routechutney->CheckForClass($class);	

                        // Check to see if class exists but method does not. If true they should see the default jelly (controller) content.

                        if ($validclass && (!isset($method)||empty($method)))
                        {

                                $newclass = new $class;

                                $newclass->index($vars);

                        }
                        elseif ($validclass && (isset($method)||!empty($method)))
                        {	
                                
                                $isprivate = $this->routechutney->IsPrivateFunction($method);
                            
                                if (!$isprivate)
                                {
                                    $newclass = new $class;

                                    // If a class and method have been requested, check to see if method exists.

                                    $methodexists = $this->routechutney->CheckForMethod($newclass,$method);

                                    if ($methodexists)
                                    {
                                                    $newclass->$method($vars);
                                    }
                                    else
                                    {

                                            // If the method check fails show the error page.
                                            
                                            $data['title'] = 'Status People &mdash; Page Not Found.';
                                            $data['homelink'] = $this->routechutney->HREF('/Fakers',$this->mod_rewrite);
                                            $data['message'] = $this->buildchutney->PageMessage('alert',array('The page you were looking for could not be found.'));
                                            $this->glaze->view('error.php',$data);

                                    }
                                }
                                else
                                {
                                    $data['title'] = 'Status People &mdash; Page Not Found.';
                                    $data['homelink'] = $this->routechutney->HREF('/Fakers',$this->mod_rewrite);
                                    $data['message'] = $this->buildchutney->PageMessage('alert',array('This page does not exist.'));
                                    $this->glaze->view('error.php',$data);
                                }

                        }
						else
                        {

                                // If the class check fails show the error page.
                               
								$class = str_replace('@','',$class);
							
							//$this->errorschutney->DebugArray($class);
							
								$valid = $this->validationchutney->ValidateString($class,'Screen Name');
	
								if ($valid[0] == false)
								{
									$data['title'] = 'Status People &mdash; Page Not Found.';
									$data['homelink'] = $this->routechutney->HREF('/Fakers',$this->mod_rewrite);
									$data['message'] = $this->buildchutney->PageMessage('alert',array('The page you were looking for could not be located.'));
									$this->glaze->view('error.php',$data);
								}
								else
								{
									$check = $this->apibind->CheckForScreenNameScore($class);
									
									if ($check > 0)
									{
										Fakers::_ShowUserData($class);
									}
									else
									{
										$data['title'] = 'Status People &mdash; Page Not Found.';
										$data['homelink'] = $this->routechutney->HREF('/Fakers',$this->mod_rewrite);
										$data['message'] = $this->buildchutney->PageMessage('alert',array('Sorry, but the page you were looking for could not be located.'));
										$this->glaze->view('error.php',$data);
									}
								}
							
/*                                 $data['title'] = 'Status People &mdash; Page Not Found.';
                                $data['homelink'] = $this->routechutney->HREF('/User/Signup',$this->mod_rewrite);
                                $data['message'] = $this->buildchutney->PageMessage('alert',array('The page you were looking for could not be located.'));
                                $this->glaze->view('error.php',$data); */

                        }
                        
		}
				
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