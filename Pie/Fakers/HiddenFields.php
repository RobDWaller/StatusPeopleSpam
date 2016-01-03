<?php namespace Fakers;

use Services\Html\Forms as HForm;

class HiddenFields
{
	protected $form;

	public function __construct()
	{
		$this->form = new HForm();
	}

	public function make()
	{
		$this->form->open(['id' => 'hidden_form']);

		$this->addFields();

		return $this->form;
	}

	protected function addFields()
	{
		$this->form->input('hidden', null, ['id' => 'twitterhandle'], null, null, '');
		$this->form->input('hidden', null, ['id' => 'twitterid'], null, null, '');
		$this->form->input('hidden', null, ['id' => 'spamscore'], null, null, '');
		$this->form->input('hidden', null, ['id' => 'spam'], null, null, '');
		$this->form->input('hidden', null, ['id' => 'potential'], null, null, '');
		$this->form->input('hidden', null, ['id' => 'checks'], null, null, '');
		$this->form->input('hidden', null, ['id' => 'followers'], null, null, '');
		$this->form->input('hidden', null, ['id' => 'firsttime'], null, null, '');
		$this->form->input('hidden', null, ['id' => 'accounttype'], null, null, '');
	} 
}