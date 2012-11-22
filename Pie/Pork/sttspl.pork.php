<?php

class sttspl
{
	
        private static $dbh;
    
	protected function ConnectionString()
	{
		
		// Remember to update this information when you build a new application.
		
                try {
			
                    if (!$this->dbh)
                    {
                        $this->dbh = new PDO("mysql:host=".__DBSTT_HOSTNAME.";dbname=".__DBSTT_NAME, __DBSTT_USERNAME, __DBSTT_PASSWORD);
                    }
                    
                    return $this->dbh;
		}
		catch(PDOException $e)
		{
			echo 'Database connection failure. Please reload page. If problem persists contact info@statuspeople.com';
                        die();
		}
		
	}
	
        public function SelectRecord($query,$params = null)
	{
		
		$dbh = $this->ConnectionString();
							
		$prep = $dbh->prepare($query);
		
		if (isset($params))
		{
			$this->BuildParams($params,$prep);
		}
		
		$prep->execute();
		
		$result = $prep->fetch(PDO::FETCH_NUM);
		
		return $result;
		
	}
        
	public function SelectRecords($query,$params = null)
	{
		
		$dbh = $this->ConnectionString();
		
		$prep = $dbh->prepare($query);
		
		if (isset($params))
		{
			$this->BuildParams($params,$prep);
		}
		
		$prep->execute();
		
		$result = $prep->fetchAll();
		
		return $result;
		
	}
	
	public function SelectCount($query,$params = null)
	{
		
		$dbh = $this->ConnectionString();
		
		$prep = $dbh->prepare($query);
		
		if (isset($params))
		{
			$this->BuildParams($params,$prep);
		}
		
		$prep->execute();
		
		$result = $prep->fetchColumn();
		
		return $result;
		
	}
	
	public function InsertRecord($query,$params = null)
	{
		
		$dbh = $this->ConnectionString();
		
		$prep = $dbh->prepare($query);
		
		if (isset($params))
		{
			$this->BuildParams($params,$prep);
		}
		
		$prep->execute();
		
		$result = $dbh->lastInsertID();
		
		return $result;
		
	}
	
        public function UpdateRecord($query, $params)
	{
		
		$dbh = $this->ConnectionString();
		
		$prep = $dbh->prepare($query);
		
		$this->BuildParams($params,$prep);
		
		$result = $prep->execute();
		
		return $result;
		
	} 
	
	protected function BuildParams($params,$prep)
	{
		
		foreach ($params as $key => $obj)
		{
			
			$field = ':'.$key;
			$type = $this->SetParam($obj[1]);
			
			$prep->bindParam($field, $obj[0], $type, $obj[2]);
				
		}
		
	}
	
	// Set Param returns the relevant PDO param constant.
	
	protected function SetParam($type)
	{
	
		$param = PDO::PARAM_STR;
	
		if ($type == 'INT')
		{
			$param = PDO::PARAM_INT;	
		}
		elseif ($type == 'BOOL')
		{
			$param = PDO::PARAM_BOOL;	
		}
		
		return $param;
		
	}
	
	protected function DebugQuery($prep)
	{
		
		echo '<pre>';
		
		print_r($prep->debugDumpParams());
		
		echo '</pre>';
		
		die();
		
	}
	
}

?>