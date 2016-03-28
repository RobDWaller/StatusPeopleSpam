<?php namespace Fakers\Payment;

class UkPayment extends AbstractPayment
{
	public function __construct($amount, $duration)
	{
		parent::__construct($amount, $duration);
	}
}