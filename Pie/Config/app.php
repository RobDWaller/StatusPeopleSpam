<?php 

return [
	'test' => 'localhost.localdomain',
	'server_name'=>[
		'local' => 'local.statuspeople.com',
		'test' => 'test.statuspeople.com',
		'production' => 'fakers.statuspeople.com'
	],
	'down' => false,
	'connection' => [
		'default' => 'statuspeople_spam'
	],
	'storage' => [
		'local' => '/var/www/spam/Pie/Storage',
		'test' => '/var/test/html/Pie/Storage',
		'production' => '/var/spam/html/Pie/Storage'
	]
];	