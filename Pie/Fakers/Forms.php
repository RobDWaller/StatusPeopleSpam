<?php namespace Fakers;

use Services\Html\Forms as HtmlForms;

class Forms
{
	public function __construct()
	{
		$this->form = new HtmlForms;
	}

	public function adminLoginForm()
	{
		return $this->form->open([
				'action' => '/Admin/Process',
				'method' => 'post'
			])
			->input('text', 'email', [], 'Email', 'email')
			->input('password', 'password', [], 'Password', 'password')
			->input('submit', 'Login');
	}

	public function postHandleForm($url)
	{
		return $this->form
			->open([
					'id'=>'screenNameAction',
					'class'=>'screenNameAction',
					'action'=>$url,
					'method'=>'post'
			])
			->input('text','handle',['id'=>'handle'],'Twitter Handle','handle')
			->input('submit','save');
	}

	public function accountDropDownForm($children)
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
			);
	}
}