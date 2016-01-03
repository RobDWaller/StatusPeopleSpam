<?php namespace Helpers;

use Services\Routes\Redirector;
use Services\Messages\Html;

trait Messages
{
	public function buildMessages(Redirector $redirect)
	{
		if ($redirect->hasMessages()) {
			$html = new Html($redirect->getMessages());

			return $html->buildMessages();
		}

		return null;
	}
}