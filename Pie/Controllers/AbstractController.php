<?php namespace Controllers;

abstract class AbstractController
{
	protected $twitterbind;
	protected $routechutney;
	protected $glaze;
	protected $dbbind;
	protected $mod_rewrite;

	public function __construct()
	{
		$this->glaze = new \Glaze();
		$this->dbbind = new \DBRequests();
		$this->twitterbind = new \TwitterRequests();
		$this->routechutney = new \Route();
		$this->mod_rewrite = true;
	}
}