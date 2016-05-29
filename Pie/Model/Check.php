<?php namespace Model;

class Check extends AbstractModel
{
	protected $table = 'spsp_checks';

	protected $connection = 'statuspeople_spam';

	public function getUsersToCheck($users, $time)
	{
		$this->query = "SELECT *
					FROM {$this->table}
					WHERE accounttype = 1 
					AND live = 1 
					AND userid IN({$this->inString('userid', $users)}) 
					AND lastcheck < :time
					ORDER BY lastcheck ASC
					LIMIT 0,1";
		
		$this->params = [
			'time' => $time
		];
		
		return $this->get();
	}

	public function updateLastCheckTime($twitterid, $screen_name, $time)
    {
        $this->query = "UPDATE {$this->table}
                    SET lastcheck = :time
                    WHERE twitterid = :twitterid AND screen_name = :screen_name";
        
        $this->params = ['time' => $time,
                        'screen_name' => $screen_name,
                        'twitterid' => $twitterid];
        
        return $this->update();   
    }

    public function updateUsersToCheckTime($twitterid, $screen_name, $time)
    {
        $this->query = "UPDATE {$this->table}
                    SET updated = :time
                    WHERE twitterid = :twitterid AND screen_name = :screen_name";
        
        $this->params = ['time' => $time,
                        'screen_name' => $screen_name,
                        'twitterid' => $twitterid];
        
        return $this->update();
    }
}