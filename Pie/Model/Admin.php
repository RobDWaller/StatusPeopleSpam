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
            
        $this->params = ['email' => $email];
        
        return $this->get();
	}	

	public function addAdmin($email, $password)
	{
		$time = time();

		$this->query = "INSERT INTO {$this->table} (email, password, created)
			VALUES (:email, :password, {$time})";

		$this->params = ['email' => $email,
			'password' => $password];

		return $this->create();
	}

	public function deleteAdmin($email) 
	{
		$this->query = "DELETE FROM {$this->table}
			WHERE email = :email";

		$this->params = ['email' => $email];

		$this->delete();
	}
}