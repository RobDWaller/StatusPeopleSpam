<?php namespace Fakers\Payment;

class AbstractPayment
{
	protected $tax = 20;

	protected $currency = 'GBP';

	protected $amount;

	protected $duration;

	protected $totalDuration;

	public function __construct($amountType, $durationType)
	{
		$this->setAmount($amountType);

		$this->setDuration($durationType);
	}

	public function getTax()
	{
		return round(($this->getSubTotal()/100) * $this->tax, 2);
	}

	public function getSaving()
	{
		return round($this->getSubTotal() - ($this->ammount * $this->totalDuration), 2);
	}

	public function getTotal()
	{
		return $this->getSubTotal() + $this->getTax();
	}

	public function getSubTotal()
	{
		return round($this->amount * $this->duration, 2);
	}

	public function getCurrency()
	{
		return $this->currency;
	}

	public function setAmount($type) 
	{
		if ($type == 1) {
			$this->amount = 3.49;
		}

		if ($type == 2) {
			$this->amount = 9.99;
		}

		if ($type == 3) {
			$this->amount = 29.99;
		}
	}

	public function setDuration($type)
	{
		if ($type == 1) {
			$this->duration = 1;
			$this->totalDuration = $this->duration;
		}

		if ($type == 2) {
			$this->duration = 5;
			$this->totalDuration = 6;
		}

		if ($type == 3) {
			$this->duration = 10;
			$this->totalDuration = 12;
		}
	}
}