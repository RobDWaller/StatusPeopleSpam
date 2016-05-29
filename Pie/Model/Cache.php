<?php namespace Model;

class Cache extends AbstractModel
{
	protected $table = 'spsp_cache';

	protected $connection = 'statuspeople_spam';

	public function getCache($userId)
	{
		$this->query = "SELECT *
					FROM {$this->table}
					WHERE userid = :userid
					ORDER BY created DESC";
		
		$this->params = ['userid' => $userId];
		
		return $this->get();
	}

	public function addCache($userid, $languages, $stats, $spam, $created)
	{
		$this->query = "INSERT INTO {$this->table} (userid, lang, averages, fakers, created)
					VALUES (:userid, :lang, :averages, :spam, :created)";
		
		$this->params = ['userid' => $userid,
					 	'lang' => $languages,
					 	'averages'=> $stats,
					 	'spam'=> $spam,
					 	'created' => $created];
		
		return $this->create();
	}

	public function updateCache($userid, $languages, $stats, $spam, $created)
	{
		$this->query = "UPDATE {$this->table} 
					SET lang = :lang, averages = :averages, fakers = :spam, created = :created
					WHERE userid = :userid";
		
		$this->params = ['userid' => $userid,
					 	'lang' => $languages,
					 	'averages'=> $stats,
					 	'spam'=> $spam,
					 	'created' => $created];
		
		return $this->update();
	}
}