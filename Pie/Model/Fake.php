<?php namespace Model;

use Model\AbstractModel;
use Services\Database\Collection;

class Fake extends AbstractModel
{
	protected $table = 'spsp_fakes';

	protected $connection = 'statuspeople_spam';

	public function weekBlockCount()
	{
		$time = strtotime('-1 Week');	

		$this->query = "SELECT count(*) 
			FROM {$this->table}
			WHERE live = 0
			AND created >= $time";	

		return $this->count();
	}

	public function monthBlockCount()
	{
		$time = strtotime('-1 Month');

		$this->query = "SELECT count(*) 
			FROM {$this->table}
			WHERE live = 0
			AND created >= $time";

		return $this->count();
	}

	public function findNewBlocks($count)
	{
		$this->query = "SELECT screen_name, avatar, created 
			FROM {$this->table}
			WHERE live = 0
			ORDER BY created DESC
			LIMIT 0, :count";

		$this->params = ['count' => $count];	

		return $this->get();
	}

	public function AddFakes($insertstring)
    {
        $query = "INSERT IGNORE INTO {$this->table} (userid, twitterid, screen_name, avatar, created)
                    VALUES $insertstring";
        
        return $this->create();
    }
}