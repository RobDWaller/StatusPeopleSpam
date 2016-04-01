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
		'local' => '/var/spam/html/Pie/Storage',
		'test' => '/var/spam/html/Pie/Storage',
		'production' => '/var/spam/html/Pie/Storage'
	]
];	