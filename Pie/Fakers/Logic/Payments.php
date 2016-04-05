<?php namespace Fakers\Logic;

use Model\Purchase;
use Model\Valid;
use Services\Hasher\Hash;
use Fakers\UnitConverter;
use Helpers\Payment as PaymentHelper;

class Payments
{
	use PaymentHelper;

	protected $purchase;

	protected $valid;

	protected $hash;

	public function __construct()
	{
		$this->hash = new Hash;	

		$this->purchase = new Purchase;

		$this->valid = new Valid;
	}

	public function addPayment($post)
	{
		$userId = $this->hash->decode($post->userId);

		$transactionId = $this->generateTransactionId($userId);

		$currency = $this->paymentIdToCurrency($post->currency);

		$amount = $this->calculateSubscriptionAmount($post->currency, $post->type, $post->duration);

		return $this->purchase->addPayment(
			$userId,
			$transactionId,
			$currency,
			$amount,
			$post->type,
			1,
			time()
		);
	}

	public function updateSubscriptionPeriod($purchaseId, $post)
	{
		$userId = $this->hash->decode($post->userId);

		$valid = $this->valid->findUserValid($userId);

		if ($valid->count() >= 1) {
			return $this->valid->updateValid(
				$valid->first()->id, 
				$purchaseId, 
				$userId, 
				$this->paymentTimestamp($post->duration, $valid->first()->valid)
			);
		}

		return $this->valid->createValid($purchaseId, $userId, $this->paymentTimestamp($post->duration));
	}
}