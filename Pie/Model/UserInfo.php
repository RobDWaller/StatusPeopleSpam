<?php namespace Model;

use Model\AbstractModel;
use Services\Database\Collection;

class UserInfo extends AbstractModel
{
	protected $table = 'spsp_user_info';

	protected $connection = 'statuspeople_spam';

	public function findScreenName($screenName)
	{
		$this->query = "SELECT *
                FROM {$this->table}
                WHERE screen_name = :screen_name
                AND live = 1";
            
        $this->params = ['screen_name' => [$screenName, 'STR', 140]];
        
        return $this->get();
	}

	public function findNewUsers($limit = 10)
	{
		$this->query = "SELECT *
			FROM {$this->table}
			ORDER BY created DESC
			LIMIT {$limit}";

		return $this->get();
	}	
}