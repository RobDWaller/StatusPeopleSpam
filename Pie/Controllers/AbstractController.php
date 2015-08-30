<?php namespace Controllers;

use Services\Routes\Loader;
use Services\Authentication\Auth;
use Services\Fakers\FakersForms;
use Services\Encryption\Encrypt;
use Services\Requests\Read;
use Services\Validation\Validator;
use Services\Requests\Server;

abstract class AbstractController
{
	protected $twitterbind;
	protected $routechutney;
	protected $glaze;
	protected $dbbind;
	protected $mod_rewrite;
	protected $auth;
	protected $data;
	protected $form;
	protected $forms;
	protected $errorschutney;
	protected $cryptor;
	protected $requests;
	protected $validator;
	protected $build;
	protected $sessions;
	protected $validation;
	protected $curl;
	protected $server;
	protected $payments;

	public function __construct()
	{
		$this->glaze = new \Glaze();
		$this->dbbind = new \DBRequests();
		$this->twitterbind = new \TwitterRequests();
		$this->routechutney = new \Route();
		$this->loader = new Loader;
		$this->mod_rewrite = true;
		$this->auth = new Auth;
		$this->form = new FakersForms;
		$this->forms = new \Forms;
		$this->errorschutney = new \Errors();
		$this->cryptor = new Encrypt;
		$this->requests = new Read;
		$this->validator = new Validator;
		$this->build = new \Build;
		$this->sessions = new \Sessions;
		$this->validation = new \Validation;
		$this->curl = new \CurlRequests;
		$this->server = new Server;
		$this->payments = new \PaymentRequests;
	}

	public function securePage($vars)
	{
		if ($vars['p']!=='yhd763jei')
		{
			$this->loader->fail();
		}
	}

	public function genericPageInfo($title = null, $url = null)
	{
		$this->data['title'] = $title ? $title : 'StatusPeople Fake Followers';
		$this->data['homelink'] = $this->routechutney->HREF($url,$this->mod_rewrite);
			
		$this->data['logout'] = 2;
		
		if (isset($this->auth->user()->type)&&($this->auth->user()->type==0||$this->auth->user()->type==1))
		{
			$this->data['logout'] = 1;
		}
		else if ($this->auth->user()->type>=2)
		{
			$this->data['logout'] = 0;
			$this->data['accountform'] = $this->buildAccountsForm(
				$this->dbbind->YourChildren(
					$this->auth->user()->primaryid
				)
			);
		}
		
		$this->data['menu'] = $this->buildMenu();
	}

	protected function buildMenu()
	{
		return '<ul><li><a href="https://statuspeople.com"><span class="ico3">&</span> Website</a></li>
		<li><a href="https://blog.statuspeople.com"><span class="ico3">%</span> Blog</a></li></ul>';
	}

	protected function buildAccountsForm($children)
	{
		$parent = $this->dbbind->GetUserInfo($this->auth->user()->primaryid);

		if (!empty($children) && 
			($this->auth->user()->type>=3) || 
			($this->auth->user()->primaryid!=$this->auth->user()->userid)) {

			$children = $this->processChildrenData($parent,$children);

			$imagestring = '<img src="'.str_replace('http:','https:',$parent[3]).'" height="30" width="30" />';
			
			$output = $imagestring.$this->form->accountDropDownForm($children)->build();
		}
		else
		{
			$imagestring = '<img src="'.str_replace('http:','https:',$parent[3]).'" height="30" width="30" /><div id="accountname">'.$parent[2].'</div>';
			$output = $imagestring;
		}
		
		return $output;
	}

	protected function accountForm($children)
	{
		return $this->form->open(
				['id'=>'changeaccountform','action'=>'/Fakers/SwitchAccount','method'=>'post']
			)
			->options(
				$children,
				'account',
				['class'=>'accountselection icon','data-tip'=>'ChangeAccount'],
				null,
				null,
				'screen_name',
				'twitterid',
				$this->auth->user()->userid
			)
			->build();
	}

	protected function processChildrenData($parent,$children)
	{
		return $this->obscureArrayIds(array_merge($this->buildParent($parent),$children),'twitterid');
	}

	protected function buildParent($parent)
	{
		return [0=>[
			'id'=>$parent[0],
			'twitterid'=>$parent[1],
			'screen_name'=>$parent[2],
			'avatar'=>$parent[3],
			'updated'=>$parent[4],
			'created'=>$parent[5]
		]];
	}

	protected function obscureArrayIds($data,$key)
	{
		if (!empty($data)) {
			
			foreach ($data as $value){
				$value[$key] = \Validation::ObscureNumber($value[$key],SALT_ONE);
			}

		}
		
		return $data;
	}
}