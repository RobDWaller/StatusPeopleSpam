<!doctype html> 
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]--> 
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]--> 
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]--> 
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]--> 
<head> 
    <meta charset="utf-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
 
    <title><?php echo $title; ?></title> 
    <meta name="description" content=""> 
    <meta name="author" content=""> 
 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="shortcut icon" href="/Pie/Crust/Template/favicon.ico"> 
    <link rel="apple-touch-icon" href="/Pie/Crust/Template/apple-touch-icon.png"> 
	<link href="http://blog.statuspeople.com/Posts/RSS" rel="alternate" title="StatusPeople Blog" type="application/rss+xml">
 
    <link rel="stylesheet" href="/Pie/Crust/Template/css/style.css?v=2"> 
    <link rel="stylesheet" href="/Pie/Crust/Template/css/tablets.css?v=2">
	<!--<link rel="stylesheet" id="sptwstyle" href="/Pie/Crust/SocialMedia/css/default.css" />--> 
    <script src="/Pie/Crust/Template/js/libs/modernizr-1.7.min.js"></script> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="http://tools.statuspeople.com/Pie/Crust/Template/js/newhighcharts/js/highcharts.js"></script>
	<script src="http://tools.statuspeople.com/Pie/Crust/Template/js/plugins/jquery.cookie.js"></script>	
</head> 
<body> 
	<div class="bgholder">
		<header>
			<div class="header">
				<div class="threea a">
					<a href="<?=$homelink;?>"><img src="http://tools.statuspeople.com/Pie/Crust/Template/img/logo_white_hires_compressed.png" height="30" width="58" alt="StatusPeople" /></a>
				</div>
				<div class="threeb a">
					<?php
					if ($logout == 1)
					{
						echo $menu;	
					}
					else
					{
						echo '<ul><li><a href="" class="ico icon" data-tip="Search for Friends" id="friendsearch">s</a></li></ul>';
					}	
					?>
				</div>
				<div class="threea">
					<?php
					if ($logout != 1)
					{
						echo '<ul><li><a href="/Fakers/Dashboard" class="ico3 icon" data-tip="Fakers Dashboard">"</a></li><li><a href="/Fakers/Followers" class="ico icon" data-tip="Your Followers">t</a></li></ul>';	
					}
					?>
				</div>
			</div>
			<?=$message;?>
		</header>