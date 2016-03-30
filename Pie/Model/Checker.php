<?php namespace Model;

class Checker extends AbstractModel
{
	public function getCheckers($start, $limit, $time)
	{
		$this->query = "SELECT sc.userid,FROM_UNIXTIME(sv.valid),FROM_UNIXTIME(sc.updated)
						FROM spsp_checkers AS sc
						JOIN spsp_valid AS sv ON sc.userid = sv.userid
						WHERE sv.valid > :time
						ORDER BY sc.updated ASC
						LIMIT :start, :limit";

		$this->params = [
			'time' => [$time, 'INT', 0]
			'start' => [$start, 'INT', 0]	
			'limit' => [$limit, 'INT', 0]
		];

		return $this->get();
	}

	public function updateCheckerTime($userid, $updated)
	{
		$this->query = "UPDATE spsp_checkers
					SET updated = :updated
					WHERE userid = :userid";
		
		$this->params = ['userid' => [$userid , 'INT' , 0 ],
					   ['updated' => [$updated,'INT', 0]];
		
		return $this->update();
	}
}