<?php namespace Fakers\Payment;

class EuPayment extends AbstractPayment
{
	public function __construct($amount, $duration)
	{
		parent::__construct($amount, $duration);
	}

	public function setAmount($type) 
	{
		if ($type == 1) {
			$this->amount = 4.49;
		}

		if ($type == 2) {
			$this->amount = 12.99;
		}

		if ($type == 3) {
			$this->amount = 39.99;
		}
	}
}