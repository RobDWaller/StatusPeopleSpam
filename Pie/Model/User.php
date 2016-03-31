<?php namespace Model;

use Model\AbstractModel;

class User extends AbstractModel
{
	protected $table = 'spsp_users';

	protected $connection = 'statuspeople_spam';

	public function findUserDetails($id)
	{
		$this->query = "SELECT u.id, u.twitterid, ui.screen_name, ui.avatar,
				ud.email, ud.title, ud.forename, ud.surname
                FROM {$this->table} AS u 
                JOIN spsp_user_info AS ui ON u.twitterid = ui.twitterid 
                LEFT JOIN spsp_user_details AS ud ON u.twitterid = ud.twitterid
                WHERE u.twitterid = :twitterid
                AND u.live = 1 AND ui.live = 1";
            
        $this->params = ['twitterid' => [$id, 'INT', 0]];
        
        return $this->get();
	}

	public function findUserDetailsByScreenName($screenName)
	{
		$this->query = "SELECT u.id, u.twitterid, ui.screen_name, ui.avatar,
				ud.email, ud.title, ud.forename, ud.surname
                FROM {$this->table} AS u 
                JOIN spsp_user_info AS ui ON u.twitterid = ui.twitterid 
                LEFT JOIN spsp_user_details AS ud ON u.twitterid = ud.twitterid
                WHERE ui.screen_name = :screenName
                AND u.live = 1 AND ui.live = 1";
            
        $this->params = ['screenName' => [$screenName, 'STR', 140]];
        
        return $this->get();
	}	

	public function findNewUsers($limit = 10)
	{
		$this->query = "SELECT u.id, u.twitterid, ui.screen_name, ui.avatar, u.created
			FROM {$this->table} as u
			JOIN spsp_user_info as ui ON u.twitterid = ui.twitterid
			WHERE u.live = 1 AND ui.live = 1
			ORDER BY u.created DESC
			LIMIT {$limit}";

		return $this->get();
	}

	public function getTwitterDetails($twitterid)
    {
        $this->query = "SELECT *
                    FROM spsp_users
                    WHERE twitterid = :twitterid AND live = 1";
        
        $this->params = ['twitterid' => [$twitterid, 'INT', 0]];
        
        return $this->get();
    }
}