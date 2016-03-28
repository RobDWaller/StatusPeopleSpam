<?php namespace Model;

use Model\AbstractModel;
use Services\Database\Collection;

class Purchase extends AbstractModel
{
	protected $table = 'spsp_purchases';

	protected $connection = 'statuspeople_spam';

	public function findUserPurchases($id)
	{
		$this->query = "SELECT p.*, v.valid
			FROM {$this->table} as p
			LEFT JOIN spsp_valid as v ON p.id = v.purchaseid
			WHERE p.userid = :twitterid
			ORDER BY p.created DESC
			LIMIT 0,20";

		$this->params = ['twitterid' => [(int) $id, 'INT', 0]];

		return $this->get();
	}

	public function addPayment($userId, $transactionId, $currency, $amount, $type, $complete = 0, $created)
	{
		$this->query = "INSERT INTO {$this->table} 
			(userid, transactionid, currency, amount, type, complete, created)
			VALUES (:userid, :transactionid, :currency, :amount, :type, :complete, :created)";

		$this->params = [
			'userid' => [$userId, 'INT', 0],
			'transactionid' => [$transactionId, 'INT', 0],
			'currency' => [$currency, 'STR', 3],
			'amount' => [$amount, 'INT', 0],
			'type' => [$type, 'INT', 0],
			'complete' => [$complete, 'INT', 0],
			'created' => [$created, 'INT', 0]
		];

		return $this->create();
	}
}