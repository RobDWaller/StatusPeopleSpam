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
}