<?php

class MainRequests Extends DB
{

	protected $connection = 'statuspeople';
	
	public function GetEmailAddresses()
	{
		$query = "SELECT email,forename,surname
					FROM stp_users";
		
		$result = $this->SelectRecords($query);
		
		return $result;
	}
	
}

?>