<?php

/* Email Chutney is a very simple method for sending transactional emails. It should not be used for mass mailing
and we would strongly suggest you look into a service like Amazon SES or CampaignMonitor if you want to do this. 

The SendEmail method accepts four simple variables...

$to = Who you wish to send the email too.
$subject = Subject line of the email to be sent.
$message = The content of the email you wish to send.
$headers = An array of headers to be associated with the email. array('from'=>'','reply'=>'','return'=>'')

Note the this method will send out an HTML email so feel free to add HTML code to the $message. */

//namespace PorkPie\Chutney;

class Email
{

	public function SendEmail($to, $subject, $message, $headers)
	{
		
		// Turns headers array into header string.
		$header = self::BuildHeaders($headers);
		
		// Sends email.
		mail($to, $subject, $message, $header);
		
	}	
	
	protected function BuildHeaders($headers)
	{
		
		$output = 'From: '.$headers['from']."\r\n".'Reply-To: '.$headers['reply']."\r\n".'Return-Path: '.$headers['return']."\r\n".'X-Mailer: PHP/'.phpversion()."\r\n".'Content-type: text/html;'."\r\n";
		
		return  $output;
		
	}
	
}

?>