<?php namespace Services\Authentication;

use Services\Object\Builder;
use Services\Routes\Redirector;
use Services\Authentication\Sessions;
use Services\Config\Loader;

class Auth
{
	protected $object;
	protected $redirect;
	protected $session;
	protected $config;

	public function __construct()
	{
		$this->object = new Builder;
		$this->redirect = new Redirector;
		$this->session = new Session;
		$this->config = new Loader;
	}

	public function id()
	{
		return !isset($this->user()->userid) ? false : $this->user()->userid;
	}

	public function twitterId()
	{
		return !isset($this->user()->twitterid) ? false : $this->user()->twitterid;
	}

	public function user()
	{
		return $this->object->ArrayToObject($this->session->getAll());
	}

	public function isLogin()
	{
		if ($this->id() < 1) {
        	$this->logout();
		}
	}

	public function logout()
	{
		$this->session->destroy();

		$this->session->destroyCookies();
		
		$this->redirect->to('/Fakers/V/1');
	}

	public function login($userid, $type = 0)
	{
		$this->session->set('userid', $userid);
		$this->session->set('primaryid', $userid);
		$this->session->set('type', $type);
	}

	public function isAdmin()
	{
		return in_array($this->twitterId(), $this->config->get('app.admins'));
	}
}