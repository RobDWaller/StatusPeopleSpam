<?php namespace Services\Routes;

class Redirector
{
	public function messages($type, array $messages)
	{
		$_SESSION['messages'] = [$type=>$messages];

		return $this;
	}

	public function to($location)
	{	
		header('Location: http://' . $_SERVER['HTTP_HOST'] . $location);
		die();
	}
}