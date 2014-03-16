<?php

class Deepdive
{
	
    private static $dbh;
    
	protected function ConnectionString()
	{
		
		// Remember to update this information when you build a new application.
		
		try {
			
			if (!$this->dbh)
			{
				$this->dbh = new PDO("mysql:host=".__DBDV_HOSTNAME.";dbname=".__DBDV_NAME, __DBDV_USERNAME, __DBDV_PASSWORD);
			}
			
			return $this->dbh;
		}
		catch(PDOException $e)
		{
			echo 'Database connection failure. Please reload page. If problem persists contact info@statuspeople.com';
            die();
        }
		
	}
	
	// Select Record fetches a single row from the database
	
	public function SelectRecord($query,$params = null)
	{
		
		$dbh = self::ConnectionString();
							
		$prep = $dbh->prepare($query);
		
		if (isset($params))
		{
			self::BuildParams($params,$prep);
		}
		
		$prep->execute();
		
		$result = $prep->fetch(PDO::FETCH_NUM);
		
		return $result;
		
	}
	
	// Select Records returns multiple rows from the database
	
	public function SelectRecords($query,$params = null)
	{
		
		$dbh = self::ConnectionString();
		
		$prep = $dbh->prepare($query);
		
		if (isset($params))
		{
			self::BuildParams($params,$prep);
		}
		
		$prep->execute();
		
		$result = $prep->fetchAll();
		
		return $result;
		
	}
	
	
	// Select Count returns a Count of rows from the database. Remember to use COUNT(*).
	
	public function SelectCount($query,$params = null)
	{
		
		$dbh = self::ConnectionString();
					
		$prep = $dbh->prepare($query);
		
		if (isset($params))
		{
			self::BuildParams($params,$prep);
		}
		
		$prep->execute();
		
		$result = $prep->fetchColumn();
		
		return $result;
		
	}
	
	// Insert Record inserts a single record into the database and returns the new record id.
	
	//Updated
	
	public function InsertRecord($query,$params = null)
	{
		
		$dbh = self::ConnectionString();
		
		$prep = $dbh->prepare($query);
		
		if (isset($params))
		{
			self::BuildParams($params,$prep);
		}
		
		$prep->execute();
		
		$result = $dbh->lastInsertID();
		
		return $result;
		
	}
	
	// Update Record will update the relevant records in the database.
	
	public function UpdateRecord($query, $params)
	{
		
		$dbh = self::ConnectionString();
		
		$prep = $dbh->prepare($query);
		
		self::BuildParams($params,$prep);
		
		$result = $prep->execute();
		
		return $result;
		
	} 
	
	// Build Params binds the relevant parameters to your PDO prepared statement. 
	
	protected function BuildParams($params,$prep)
	{
		
		foreach ($params as $key => $obj)
		{
			
			$field = ':'.$key;
			$type = self::SetParam($obj[1]);
			
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