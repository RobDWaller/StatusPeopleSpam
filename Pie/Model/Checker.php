<?php namespace Model;

class Checker extends AbstractModel
{
	protected $table = 'spsp_fakes';

	protected $connection = 'statuspeople_spam';

	public function getCheckers($start, $limit, $time)
	{
		$this->query = "SELECT sc.userid,FROM_UNIXTIME(sv.valid),FROM_UNIXTIME(sc.updated)
						FROM spsp_checkers AS sc
						JOIN spsp_valid AS sv ON sc.userid = sv.userid
						WHERE sv.valid > :time
						ORDER BY sc.updated ASC
						LIMIT :start, :limit";

		$this->params = [
			'time' => $time,
			'start' => $start,	
			'limit' => $limit
		];

		return $this->get();
	}

	public function updateCheckerTime($userid, $updated)
	{
		$this->query = "UPDATE spsp_checkers
					SET updated = :updated
					WHERE userid = :userid";
		
		$this->params = ['userid' => $userid,
					   'updated' => $updated];
		
		return $this->update();
	}
}