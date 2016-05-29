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
			->input('text', 'login_email', ['id' => 'login_email'], 'Email', 'email')
			->input('password', 'login_password', ['id' => 'login_password'], 'Password', 'password')
			->input('submit', 'login', null, null, null, 'Login');
	}

	public function postHandleForm($url)
	{
		return $this->form
			->open([
					'id' => 'screenNameAction',
					'class' => 'screenNameAction',
					'action' => $url,
					'method' => 'post'
			])
			->input('text', 'handle', ['id' => 'handle'], 'Twitter Handle', 'handle')
			->input('submit', 'search', null, null, null, 'Search');
	}

	public function adminPaymentForm($url, $action, $userId)
	{
		return $this->form
			->open([
					'id' => 'adminPaymentForm',
					'class' => 'adminPaymentForm',
					'action' => $url,
					'method' => 'post'
			])
			->input('hidden', 'userId', null, null, null, $userId)
			->options([['1', 'Basic'], ['2', 'Premium'], ['3', 'Agency']], ['name' => 'type'], 'Subscription Type')
			->options([['1', 'GBP'], ['2', 'USD'], ['3', 'EUR']],  ['name' => 'currency'], 'Currency')
			->options([['1', '1 Month'], ['2', '6 Months'], ['3', '12 Months']],  ['name' => 'duration'], 'Subsrciption Duration')
			->input('submit', 'payment_submit', null, null, null, $action);
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