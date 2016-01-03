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
}