<?php

/* DB Pork provides a very simple way to connect and query your database. It makes use of 
PDO and prepared statements, we believe this provides a relatively flexible and secure
means of interacting with your databases.

There are five basic ways to query the database: Select Record, Select Records, Select Count,
Insert Record and Update Record. All data is passed back using index arrays, we will build in
an associative format in a later version.

To make a query first update the ConnectionString method with your database details. Then call
one of the relevant methods. All you need to do is pass a sql query and an array of PDO params.
The array of params should take the following form...

columnname
	value
	type
	length
	
So a database query will look something like this... 

SelectRecord("SELECT user FROM tblUsers WHERE name = :name", array('name'=>array('Pork','STR',150))); 

Tip: You can debug PDO prepared statements using the following line of code...

$this->DebugQuery($prep); */

//namespace PorkPie\Pork;

class DBAPI
{
	
        private static $dbh;
    
	protected function ConnectionString()
	{
		
		// Remember to update this information when you build a new application.
		
		try {
			
                    if (!$this->dbh)
                    {
                        $this->dbh = new PDO("mysql:host=".__DBAPI_HOSTNAME.";dbname=".__DBAPI_NAME, __DBAPI_USERNAME, __DBAPI_PASSWORD);
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
		
		//$this->DebugQuery($prep);
		
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