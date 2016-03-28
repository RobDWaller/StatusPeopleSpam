<?php namespace Fakers;

use Helpers\Payment;
use Exception\ConverterException;

class UnitConverter
{
	use Payment;

	public static function __callStatic($method, $attributes)
	{
		$newMethod = 'get' . ucfirst($method);

		$converter = new UnitConverter();

		if (method_exists($converter, $newMethod)) {
			return $converter->$newMethod($attributes[0], $attributes[1], $attributes[2]);
		}

		throw new ConverterException('Static Method [' . $newMethod . '] could not be found.');
	}

	public function getTimestampToDate($timestamp = null)
	{
		return $timestamp != null ? date('d/m/Y H:i', $timestamp) : '';
	}

	public function getBooleanToString($state)
	{
		return $state == 1 || $state == true ? 'Yes' : 'No';
	} 

	public function getAccountTypeToString($type)
	{
		return $this->paymentAccountTypeToString($type);
	}

	public function getCurrencyStringToType($currency)
	{
		return $this->paymentCurrencyStringToType($currency);
	} 

	public function getIdToCurrency($id)
	{
		return $this->paymentIdToCurrency($id);
	}

	public function getFormatNumber($number = 0, $decimals = 0)
	{
		return number_format($number, $decimals);
	}
}