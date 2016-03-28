<?php namespace Model;

use Model\AbstractModel;
use Services\Database\Collection;

class Valid extends AbstractModel
{
	protected $table = 'spsp_valid';

	protected $connection = 'statuspeople_spam';

	public function findAccoutType($userId)
	{
    	$this->query = "SELECT *
            FROM {$this->table} as v
            JOIN spsp_purchases as p ON v.purchaseid = p.id
            WHERE v.userid = :userid
            AND p.complete = 1
            ORDER BY created DESC
            LIMIT 1";
            
        $this->params = ['userid' => [$userId, 'INT', 0]];
        
        return $this->get();
	}

    public function findUserValid($userId) 
    {
        $this->query = "SELECT * 
            FROM {$this->table}
            WHERE userid = :userid";

        $this->params = ['userid' => [$userId, 'INT', 0]];

        return $this->get();
    }

    public function updateValid($id, $purchaseId, $userId, $time)
    {
        $this->query = "UPDATE {$this->table} 
            SET purchaseid = :purchaseid, userid = :userid, valid = :valid
            WHERE id = :id";

        $this->params = [
            'purchaseid' => [$purchaseId, 'INT', 0],
            'userid' => [$userId, 'INT', 0],
            'valid' => [$time, 'INT', 0],
            'id' => [$id, 'INT', 0]
        ];

        return $this->update();
    }

    public function createValid($purchaseId, $userId, $time)
    {
        $this->query = "INSERT {$this->table} (purchaseid, userid, valid)
            VALUES (:purchaseid, :userid, :valid)";

        $this->params = [
            'purchaseid' => [$purchaseId, 'INT', 0],
            'userid' => [$userId, 'INT', 0],
            'valid' => [$time, 'INT', 0]
        ];

        return $this->create();
    }
}