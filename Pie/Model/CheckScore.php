<?php namespace Model;

class CheckScore extends AbstractModel
{
	protected $table = 'spsp_check_scores';

	protected $connection = 'statuspeople_spam';

	public function addCheckScore($twitterid, $screen_name, $spam, $potential, $checks, $followers, $created)
    {
        
        $this->query = "INSERT INTO {$this->table} (twitterid, screen_name, spam, potential, checks, followers, created)
                    VALUES (:twitterid, :screen_name, :spam, :potential, :checks, :followers, :created)";
        
        $this->params = ['twitterid' => $twitterid,
                        'screen_name' => $screen_name,
                        'spam' => $spam,
                        'potential' => $potential,
                        'checks' => $checks,
                        'followers' => $followers,
                        'created' => $created];
        
        return $this->create();
    }

    public function getScores($userId, $count)
    {
        $this->query = "SELECT *
            FROM {$this->table}
            WHERE twitterid = :twitterid
            ORDER BY created DESC
            LIMIT 0, :count";

        $this->params = [
            'twitterid' => $userId,
            'count' => $count
        ];

        return $this->get();
    }
}