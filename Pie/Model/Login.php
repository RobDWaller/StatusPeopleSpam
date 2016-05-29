<?php namespace Model;

use Model\AbstractModel;

class Login extends AbstractModel
{
	protected $table = 'spsp_admin_logins';

	protected $connection = 'statuspeople_spam';

	public function countLogins($ipAddress)
	{
		$time = strtotime('-10 Minutes');

		$this->query = "SELECT count(*)
			FROM {$this->table}
			WHERE ip_address = :ip_address
			AND created >= $time
			AND success = 0";

		$this->params = ['ip_address' => $ipAddress];

		return $this->count();
	}

	public function addLogin($ipAddress, $success)
	{
		$time = time();

		$this->query = "INSERT INTO {$this->table} (ip_address, success, created)
			VALUES (:ipAddress, :success, $time)";

		$this->params = ['ipAddress' => $ipAddress,
			'success' => $success];

		return $this->create();
	}

	public function deleteLogin($ip)
	{
		$this->query = "DELETE FROM {$this->table}
			WHERE ip_address = :ip";

		$this->params = ['ip' => $ip];

		$this->update();
	}
}