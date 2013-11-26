<?php

class MainRequests Extends Main
{
	
	public function GetEmailAddresses()
	{
		$query = "SELECT email,forename,surname
					FROM stp_users";
		
		$result = $this->SelectRecords($query);
		
		return $result;
	}
	
}

?>