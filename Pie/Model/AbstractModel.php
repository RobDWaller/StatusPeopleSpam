<?php namespace Model;

use Services\Database\Connector;
use Services\Database\Collection;

abstract class AbstractModel extends Connector
{
	protected $table;

	protected $connection;

	protected $query;

	protected $result;  

	public function count()
	{              
        $result = $this->SelectCount($this->query, $this->params);
        
        $this->clean();

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

	public function get()
	{
		$this->getRecords();

		$collection = $this->collection();

		$this->clean();

		return $collection; 
	}

	public function update()
	{
		$result = $this->UpdateRecord($this->query, $this->params);
        
        $this->clean();

        return $result;
	}

	public function create()
	{
		$result = $this->InsertRecord($this->query, $this->params);
        
        $this->clean();

        return $result;
	}

	public function delete()
	{
		$result = $this->UpdateRecord($this->query, $this->params);
        
        $this->clean();

        return $result;
	}
}