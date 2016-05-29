<?php namespace Services\Database;

use Services\Config\Loader as Config;
use Helpers\Application;
use Helpers\Database;
use Helpers\String;
use Services\Database\Object\Parameter;
use Services\Database\Object\Parameters;
use Exception\DatabaseException;
use PDO;

class Connector
{
	use Application;
	use Database;
	use String;

    protected $dbh;

    protected $connection;
    
    protected $config;
    
    protected $loader;

    public function setConnection($connection)
    {	
    	$this->connection = $connection;
    }

    protected function buildConnection($type)
    {
    	$this->dbh[$this->connection] = new PDO("mysql:
        	host=" . $this->config->get('database.' . $type . '.' . $this->connection . '.host') . ";
        	dbname=" . $this->config->get('database.' . $type . '.' . $this->connection . '.name'), 
        	$this->config->get('database.' . $type . '.' . $this->connection . '.username'), 
        	$this->config->get('database.' . $type . '.' . $this->connection . '.password'));
    }

    protected function connectionString()
	{
		$this->config = new Config;

		try {
			
            if (!isset($this->dbh[$this->connection]))
            {
                $this->isAppInTest() ? $this->buildConnection('test') : $this->buildConnection('live');
            }
	
            return $this->dbh[$this->connection];
		}
		catch(PDOException $e) {
			echo 'Database connection "' . $this->connection . '" failure. Please reload page. If problem persists contact info@statuspeople.com';
            echo $e->getMessage();
            die();
        }
		
	}
	
	// Build Params binds the relevant parameters to your PDO prepared statement. 
	
	public function defineParameters(array $parameters)
	{
		foreach ($parameters as $key => $parameter) {
			$parameterArray[] = new Parameter(
				$key, 
				$parameter, 
				$this->getParameterType($parameter), 
				$this->stringLength($parameter)
			);	
		}

		return new Parameters($parameterArray);
	}
	
	public function bindParameters(Parameters $parameters, $preparedStatement)
	{
		foreach ($parameters as $parameter) {
			$preparedStatement->bindParam($parameter->name, $parameter->value, $parameter->type, $parameter->length);
		}

		return $preparedStatement;
	}

	public function getPreparedStatement($query)
	{
		return $this->connectionString()->prepare($query);
	}

	public function buildParameters($preparedStatement, $parameters = null)
	{
		if ($parameters !== null) {
			$parameterCollection = $this->defineParameters($parameters);

			$this->bindParameters($parameterCollection, $preparedStatement);
		}
	}

	protected function executeFail($info) 
	{
		return 'SQL Code: ' . $info[0] . ' Driver Code: ' . $info[1] . ' Message: ' . $info[2];
	}

	public function execute($preparedStatement)
	{
		try {
			
			$result = $preparedStatement->execute();
		
			if ($preparedStatement->errorCode() !== '00000') {
				throw new DatabaseException(
					$this->executeFail($preparedStatement->errorInfo())
				);
			}

			return $result;
		} 
		catch (Exception $e) {
			echo $e->getMessage() . PHP_EOL;
			die();
		}
	}

	// Select Records returns multiple rows from the database
	
	public function selectRecords($query, $parameters = null)
	{
		$preparedStatement = $this->getPreparedStatement($query); 
		
		$this->buildParameters($preparedStatement, $parameters);

		$this->execute($preparedStatement);

		return $preparedStatement->fetchAll();	
	}
	
	
	// Select Count returns a Count of rows from the database. Remember to use COUNT(*).
	
	public function selectCount($query, $parameters = null)
	{
		
		$preparedStatement = $this->getPreparedStatement($query); 
		
		$this->buildParameters($preparedStatement, $parameters);

		$this->execute($preparedStatement);

		return (int) $preparedStatement->fetchColumn();
	}
	
	// Insert Record inserts a single record into the database and returns the new record id.
	
	//Updated
	
	public function insertRecord($query, $parameters = null)
	{
		
		$preparedStatement = $this->getPreparedStatement($query); 
		
		$this->buildParameters($preparedStatement, $parameters);

		$this->execute($preparedStatement);
		
		return (int) $this->dbh[$this->connection]->lastInsertID();
		
	}
	
	// Update Record will update the relevant records in the database.
	
	public function updateRecord($query, $parameters)
	{
		
		$preparedStatement = $this->getPreparedStatement($query); 
		
		$this->buildParameters($preparedStatement, $parameters);

		$this->execute($preparedStatement);
		
		return $preparedStatement->rowCount();
	} 
	
}

?>