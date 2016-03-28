<?php namespace Fakers\Payment;

class UsPayment extends AbstractPayment
{
	protected $tax = 0;

	public function __construct($amount, $duration)
	{
		parent::__construct($amount, $duration);
	}

	public function setAmount($type) 
	{
		if ($type == 1) {
			$this->amount = 5.49;
		}

		if ($type == 2) {
			$this->amount = 14.99;
		}

		if ($type == 3) {
			$this->amount = 44.99;
		}
	}
}