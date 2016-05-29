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
            
        $this->params = ['screen_name' => $screenName];
        
        return $this->get();
	}

	public function findNewUsers($limit = 10)
	{
		$this->query = "SELECT *
			FROM {$this->table}
			ORDER BY created DESC
			LIMIT :limit";

		$this->params = ['limit' => $limit];	

		return $this->get();
	}	

	public function findTwitterId($twitterId)
	{
		$this->query = "SELECT *
                FROM {$this->table}
                WHERE twitterid = :twitterid
                AND live = 1";
            
        $this->params = ['twitterid' => $twitterId];
        
        return $this->get();
	}

	public function updateUserInfo($twitterId, $screenName, $avatar)
    {
        $this->query = "UPDATE {$this->table}
					SET screen_name = :screen_name,
					avatar = :avatar
					WHERE twitterid = :twitterid AND live = 1";
        
        $this->params = ['twitterid' => $twitterId,
                    'screen_name' => $screenName,
                    'avatar' => $avatar];
        
        return $this->update();
    }
}