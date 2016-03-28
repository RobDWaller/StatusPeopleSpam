<?php namespace Controllers;

use Fakers\Payments as FakerPayments;

class Payments extends AbstractController
{
	protected $payments;

	public function __construct()
	{
		parent::__construct(true);

		$this->payments = new FakerPayments();
	}

	public function add()
	{
		$post = $this->view->post();

		$valid = $this->validator->csrf($post->csrf)
			->required('type', $post->type)
			->required('currency', $post->currency)
			->required('duration', $post->duration)
			->check();

		$valid->isFail('/Accounts/User?id=' . $post->userId);

		$purchaseId = $this->payments->addPayment($post);

		$this->payments->updateSubscriptionPeriod($purchaseId, $post);

		return $this->redirect->messages(
            $this->auth->getUserKey(), 
            ['success' => ['messages' => ['Purchase created successfully']]]
        )->to('/Accounts/User?id=' . $post->userId);
	}

	
}