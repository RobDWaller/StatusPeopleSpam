<?php

// Set the sitepath constant
$site_path = $_SERVER["DOCUMENT_ROOT"];
define ('__SITE_PATH', $site_path);

// StatusPeople Database
define('__DB_HOSTNAME','127.0.0.1');
define('__DB_USERNAME','spspan');
define('__DB_PASSWORD','criSU876ky4q');
define('__DB_NAME','statuspeople_spam');

// StatusPeople DeepDive Database
define('__DBDV_HOSTNAME','Localhost');
define('__DBDV_USERNAME','statuspeople_dv');
define('__DBDV_PASSWORD','DNLuPtufdffq8mrc');
define('__DBDV_NAME','statuspeople_deepdive');

// Sttsp Database
define('__DBSTT_HOSTNAME','127.0.0.1');
define('__DBSTT_USERNAME','sttsppl');
define('__DBSTT_PASSWORD','6eyaK15lW');
define('__DBSTT_NAME','sttsp');

// Twitter
define('CONSUMER_KEY', 'A9vtXuvPwNyJxeHpseu1tQ');
define('CONSUMER_SECRET', 'ukWQ1161TnClejQHhjEO7p4clb5yZkKSBTqG6Nt95YU');
define('OAUTH_CALLBACK', 'http://fakers.statuspeople.com/Fakers/TwitterCallback');

// PayPal

define('PAYPAL_ID','REH896CP9BA7N');
define('PAYPAL_ACTION','https://securepayments.paypal.com/cgi-bin/acquiringweb');
//define('PAYPAL_ID','rdwall_1345802011_biz@googlemail.com');
//define('PAYPAL_ACTION','https://securepayments.sandbox.paypal.com/cgi-bin/acquiringweb');

// Kred
define('KRED_APP_ID','e44e3776');
define('KRED_KEY','559ff380ecc0d07a4b3705e737b81828');

define('SALT_ONE','uiop9348me');
define('SALT_TWO','ty541jhHk72');

?>