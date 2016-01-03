<?php namespace Model;

use \DB as Connector;
use Services\Database\Collection;

abstract class AbstractModel extends Connector
{
	protected $table;

	protected $connection;

	protected $query;

	protected $result;  

	public function count($id = false)
	{
		$where = null;
		$params = [];

		if ($id) {
			$where = "WHERE id = :id";
        	$params = array('id'=>array($id,'INT',0));
		}

		$query = "SELECT COUNT(*)
                FROM {$this->table}
                $where";
                
        
        $result = $this->SelectCount($query, $params);
        
        return $result;
	}

	public function find($id)
	{
		$this->query = "SELECT *
                FROM {$this->table}
                WHERE id = :id";
            
        $this->params = array('id'=>array($id,'INT',0));
        
        return $this->get();
	}

	public function all()
	{
		$this->query = "SELECT *
                FROM {$this->table}";
            
        return $this->get();
	}

	public function live()
	{
		$query = "SELECT *
                FROM {$this->table}
                WHERE live = 1";
            
        $result = $this->SelectRecords($query);
        
        return $result;
	}

	private function getRecords()
	{
		$this->result = is_array($this->params) ? $this->SelectRecords($this->query, $this->params) :
			$this->SelectRecords($this->query);
	}

	private function collection()
	{
		return new Collection($this->result);
	}

	private function clean()
	{
		$this->query = null;
		$this->params = null;
		$this->result = null;
	}

	protected function get()
	{
		$this->getRecords();

		$collection = $this->collection();

		$this->clean();

		return $collection; 
	}
}