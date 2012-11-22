<?php

/* Errors Chutney helps you handle the errors generated by your website. It works in conjunction with 
set_error_handler found in jelly.php 

Essentially there are two options, one for debugging where all errors are displayed on screen and
one for a live site where errors are sent to you via email and the user is redirected to an error page.
To change this setting just change $IsDebug from true to false. 

You can also set a couple of options $email defines the address you want the error messages to go to
and $errormessage defines the error message you want to display to the client.*/

//namespace PorkPie\Chutney;

class Errors
{
	
	public $IsDebug = false;
	public $email = 'rdwaller1984@googlemail.com';
	public $errormessage = "An error occurred, the development team has been informed and we suggest you retry the task you were attempting. If this problem persists please contact info@statuspeople.com.";
	public $glaze;
	public $buildpork;
	
	function __construct()
	{
		
		$this->glaze = new Glaze();
		$this->buildpork = new Build();
                $this->routechutney = new Route();
		
	}

	public function ErrorHandler($number,$message,$file,$line,$vars)
	{
		
		if (error_reporting() === 0)
		{
			return true;	
		}
		// Check if application is in debug mode or not.
		elseif ($this->IsDebug)
		{
			
			$message = "<p>An error ($number) occurred on line <strong>$line</strong> in the file <strong>$file</strong></p>
			<p>$message</p>
			<pre>" .print_r($vars,1). "</pre>";
			
			error_log($message,0);
			
                        $data['title'] = 'Status People &mdash; Error!!';
                        $data['homelink'] = $this->routechutney->HREF('/User/Signup',true);
			$data['message'] = $message;
                        $data['logout'] = 0;
			
			$this->glaze->view('error.php',$data);
			
			// If error is not a notice the application will die.			
			if ( ($number !== E_NOTICE) && ($number < 2048) )
			{
				die();
			}
			
		}
		else
		{
			
			$message = "<p>An error ($number) occurred on line <strong>$line</strong> in the file <strong>$file</strong></p>
			<p>$message</p>
			<pre>" .print_r($vars,1). "</pre>";
			
			$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
			
			//if the application is not a notice the application will send the errors email, show the error page to the user and die.
			
			if ( ($number !== E_NOTICE) && ($number < 2048) )
			{
				
				error_log($message,1,$this->email,$headers);
				
                                $data['title'] = 'Status People &mdash; Error!!';
                                $data['homelink'] = $this->routechutney->HREF('/User/Signup',true);
                                $data['message'] = $this->buildpork->PageMessage('alert',array($this->errormessage));
				$data['logout'] = 0;
                                
				$this->glaze->view('error.php',$data);
				
				die();
				
			}
			
		}	
		
	}	
	
	// This helps you debug arrays by printing out an array in a readable format
	
	public function DebugArray($array)
	{
		
		echo '<pre>';
		print_r($array);
		echo '</pre>';
		die();
		
	}
	
	public function PrintArray($array)
	{
		
		echo '<pre>';
		print_r($array);
		echo '</pre>';
		
	}
	
}

?>