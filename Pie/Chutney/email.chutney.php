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

	public function SendEmail($to, $subject, $message, $headers, $marketing = 0)
	{
		
		// Turns headers array into header string.
		$header = self::BuildHeaders($headers);
		
		$email = self::MessageTemplate($subject,$message,$to,$marketing);
		
		// Sends email.
		mail($to, $subject, $email, $header);
		
	}	
	
	protected function BuildHeaders($headers)
	{
		
		$output = 'From: '.$headers['from']."\r\n".'Reply-To: '.$headers['reply']."\r\n".'Return-Path: '.$headers['return']."\r\n".'X-Mailer: PHP/'.phpversion()."\r\n".'Content-type: text/html;'."\r\n";
		
		return  $output;
		
	}
	
	protected function MessageTemplate($title,$message,$email,$marketing)
	{
		$email = '<html>
					<head>
						<title>'.$title.'</title>
					</head>
					<body style="background-color:#eef8fb;">
						<div style="width:100%; background-color:#eef8fb;">
							<table width="600px" cellpadding="0px" cellspacing="0px" align="center">
								'.($marketing?'<tr><td width="580px" align="center" style="background-color:#fefefe; padding:10px; color:#fefefe; font-size:12px; font-family:tahoma,arial;" colspan="2"><a href="http://spamtest.statuspeople.com/Fakers/Unsubscribe?e='.$email.'" style="color:#fe7d1d;">Unsubscribe From Marketing Messages From StatusPeople.com</a></td></tr>':'').'
								<tr>
									<td width="80px" height="40px" style="background-color:#36b6d5; padding:0px 10px;"><img src="http://tools.statuspeople.com/Pie/Crust/Template/img/logo_white_hires_compressed.png" height="30px" width="58px" style="padding:5px;" /></td>
									<td width="480px" height="40px" style="background-color:#36b6d5; color:#fefefe; padding:0px 10px; text-align:right;"><strong><a href="http://statuspeople.com" style="color:#fefefe; text-decoration:none; font-family:Tahoma,Arial;">Website</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://fakers.statuspeople.com" style="color:#fefefe; text-decoration:none; font-family:Tahoma,Arial;">Fakers</a></strong></td>
								</tr>
								<tr>
									<td width="580px" style="background-color:#fefefe; padding:10px;" colspan="2">
										'.$message.'
									</td>
								</tr>
								<tr>
									<td width="580px" height="40px" style="background-color:#fe7d1d; padding:10px; color:#fefefe; font-size:12px; font-family:tahoma,arial;" colspan="2">
										&copy; 2013 StatusPeople.com <a href="http://twitter.com/statuspeople" style="color:#fefefe;">@StatusPeople</a>
									</td>
								</tr>
								'.($marketing?'<tr><td width="580px" align="center" style="background-color:#fefefe; padding:10px; color:#fefefe; font-size:12px; font-family:tahoma,arial;" colspan="2"><a href="http://spamtest.statuspeople.com/Fakers/Unsubscribe?e='.$email.'" style="color:#fe7d1d;">Unsubscribe From Marketing Messages From StatusPeople.com</a></td></tr>':'').'
							</table>
						</div>
					</body>
				</html>';
		
		return $email;
	}
}

?>