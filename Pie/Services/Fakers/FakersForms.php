<?php namespace Services\Fakers;

use Services\Html\Forms;

class FakersForms
{
	public function __construct()
	{
		$this->form = new Forms;
	}

	public function postHandleForm($url)
	{
		return $this->form
			->open([
					'id'=>'addDive',
					'class'=>'addDive',
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