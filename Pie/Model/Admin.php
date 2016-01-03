<?php namespace Model;

use Model\AbstractModel;

class Admin extends AbstractModel
{
	protected $table = 'spsp_admin';

	protected $connection = 'statuspeople_spam';

	public function findEmailPassword($email)
	{
		$this->query = "SELECT *
                FROM {$this->table}
                WHERE email = :email
                AND live = 1";
            
        $this->params = ['email' => [$email, 'STR', 64]];
        
        return $this->get();
	}	
}