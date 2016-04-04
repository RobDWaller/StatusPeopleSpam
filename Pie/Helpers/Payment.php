<?php namespace Helpers;

use Fakers\Payment\UkPayment;
use Fakers\Payment\UsPayment;
use Fakers\Payment\EuPayment;

trait Payment
{
	protected $salt = 541257896335;

	public function paymentAccountTypeToString($type)
	{
		if ($type == 1) {
			return 'Basic';
		}

		if ($type == 2) {
			return 'Premium';
		}

		if ($type == 3) {
			return 'Agency';
		}

		return '';
	}

	public function paymentCurrencyStringToType($currency)
	{
		if ($currency == 'GBP') {
			return '&pound;';
		}

		if ($currency == 'USD') {
			return '&dollar;';
		}

		if ($currency == 'EUR') {
			return '&euro;';
		}

		return '';
	} 

	public function paymentIdToCurrency($id)
	{
		if ($id == 1) {
			return 'GBP';
		}

		if ($id == 2) {
			return 'USD';
		}

		if ($id == 3) {
			return 'EUR';
		}

		return '';
	}

	public function generateTransactionId($userId)
	{
		return substr(hash('sha256', $userId . time() . rand(1,9999) . $this->salt), 0, 12);
	}

	public function calculateSubscriptionAmount($currency, $type, $duration)
	{
		if ($currency == 1) {
			$payment = new UkPayment($type, $duration);
			return $payment->getSubTotal();
		}

		if ($currency == 2) {
			$payment = new UsPayment($type, $duration);
			return $payment->getSubTotal();	
		}

		if ($currency == 3) {
			$payment = new EuPayment($type, $duration);
			return $payment->getSubTotal();
		}
	}

	public function paymentTimestamp($duration, $valid = false)
	{
		if ($duration == 1) {
			return $this->generateTime('+1 Month', $valid);
		}

		if ($duration == 2) {
			return $this->generateTime('+6 Months', $valid);
		}

		if ($duration == 3) {
			return $this->generateTime('+12 Months', $valid);
		}
	}

	public function generateTime($timeString, $valid)
	{
		if (!$valid) {
			return strtotime($timeString);
		}

		return $this->alreadyValidTimestamp($timeString, $valid);
	}

	protected function alreadyValidTimestamp($timeString, $valid)
	{
		if ($timeString == '+1 Month') {
			return $valid + (3600 * 24 * 31);
		}

		if ($timeString == '+6 Months') {
			return $valid + (3600 * 24 * 183);
		}

		if ($timeString == '+12 Months') {
			return $valid + (3600 * 24 * 365);
		}		
	}
}