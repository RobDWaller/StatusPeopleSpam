<?php use Model;

class Check extends AbstractModel
{
	public function getUsersToCheck($users, $time)
	{
		$this->query = "SELECT *
					FROM spsp_checks
					WHERE accounttype = 1 
					AND live = 1 
					AND userid IN({$this->inString('userid', $users)}) 
					AND lastcheck < :time
					ORDER BY lastcheck ASC
					LIMIT 0,1";
		
		$this->params = ['userid' => [$userid, 'INT', 0],
			['time' => [$time, 'INT', 0]];
		
		return $this->get();
	}

	public function updateLastCheckTime($twitterid, $screen_name, $time)
    {
        $this->query = "UPDATE spsp_checks
                    SET lastcheck = :time
                    WHERE twitterid = :twitterid AND screen_name = :screen_name";
        
        $this->params = ['time' => [$time, 'INT' , 0 ],
                        'screen_name' => [$screen_name, 'STR', 140],
                        'twitterid' => [$twitterid, 'INT', 0]];
        
        return $this->update();   
    }
}