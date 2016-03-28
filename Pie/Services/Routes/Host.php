<?php namespace Services\Routes;

use Services\Config\Loader as Config;
use Helpers\Server;

class Host
{
	use Server;

	protected $config;

	public function __construct()
	{
		$this->config = new Config;
	}

	public function whichHost()
	{
		foreach ($this->config->get('app.server_name') as $k => $host) {
			if ($host == $this->getServer()->SERVER_NAME) {
				return $k;
			}
		}

		return 'local';
	}
}