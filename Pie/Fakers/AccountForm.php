<?php namespace Fakers;

use Services\Authentication\Auth;
use Services\Html\Forms as ACForm;

class AccountForm
{
	protected $auth;

	protected $form;

	public function __construct()
	{
		$this->auth = new Auth();
		$this->form = new ACForm();
	}

	public function make()
	{
		$this->form->open(['id' => 'account_form']);

		if ($this->auth->isLoggedIn()) {
			return $this->loggedInForm();
		}

		return $this->form;
	}

	protected function loggedInForm()
	{
		return isset($this->auth->user()->type) && $this->auth->user()->type == 3 ?
			$this->multiAccountForm() : 
			$this->standardForm();
	}

	protected function multiAccountForm() 
	{
		$this->form->options(
			$this->createAccountData(),
			'Accounts',
			['id' => 'Accounts Form'],
			null,
			null,
			'text',
			'value'
		);

		return $this->form;
	}

	protected function createAccountData()
	{
		return [
			[
				'text' => 'Select Account',
				'value' => 0
			]
		];
	}

	protected function standardForm()
	{
		$img = $this->form->img([
			'src' => $this->auth->user()->avatar,
			'width' => '48px',
			'height' => '48px'
		]);

		$label = $this->form->addLabel($this->auth->user()->screen_name, '');

		$this->form->buildFieldset($label, $img);

		return $this->form;
	}
}