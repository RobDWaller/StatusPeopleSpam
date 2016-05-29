<?php namespace Test;

use Laracasts\Integrated\Extensions\Goutte;
use Services\Authentication\Auth;

class Build extends Goutte 
{
	protected $baseUrl = 'http://localhost:80';

	public function setUp()
	{
		$_SERVER['DOCUMENT_ROOT'] = '/var/spam/html';
	}

	protected function login($id, $primaryId, $type, $url) 
	{
		return $this->visit($url . '?id=' . $id . '&pid=' . $primaryId . '&t=' . $type);
	}
}