<?php namespace Model;

class SpamScore extends AbstractModel
{
	protected $table = 'spsp_spam_scores';

	protected $connection = 'statuspeople_spam';

	public function updateSpamDetails($twitterid, $spam, $potential, $checks, $followers, $updated)
    {
        $this->query = "UPDATE {$this->table}
                    SET spam = :spam, potential = :potential, checks = :checks, followers = :followers, updated = :updated
                    WHERE twitterid = :twitterid";
        
        $this->params = ['twitterid' => $twitterid,
                        'spam' => $spam,
                        'potential' => $potential,
                        'checks' => $checks,
                        'followers' => $followers,
                        'updated' => $updated];
        
        return $this->update();
    }

    public function getScore($userId)
    {
        $this->query = "SELECT *
            FROM {$this->table}
            WHERE twitterid = :twitterid";

        $this->params = [
            'twitterid' => $userId
        ];

        return $this->get();
    }
}