<?php namespace Helpers;

use Helpers\Server;

trait Dir
{
	use Server;

	public function getCurrentDirectory()
	{
		return getcwd();
	}

	public function getRootDirectory()
	{
		return $this->getServer()->DOCUMENT_ROOT;
	}
}