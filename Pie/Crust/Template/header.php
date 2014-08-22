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
 
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"> 
	<link rel="icon" type="image/png" href="/Pie/Crust/Template/img/ico.png">
    <!--<link rel="shortcut icon" href="/Pie/Crust/Template/favicon.ico">
    <link rel="apple-touch-icon" href="/Pie/Crust/Template/apple-touch-icon.png"> -->
	<link href="http://blog.statuspeople.com/Posts/RSS" rel="alternate" title="StatusPeople Blog" type="application/rss+xml">
 
    <link rel="stylesheet" href="/Pie/Crust/Template/minify/css/style.css"> 
    <!--<link rel="stylesheet" href="/Pie/Crust/Template/css/tablets.css?v=2">-->
	<!--<link rel="stylesheet" id="sptwstyle" href="/Pie/Crust/SocialMedia/css/default.css" />--> 
    <script src="/Pie/Crust/Template/js/libs/modernizr-1.7.min.js"></script> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="/Pie/Crust/Template/js/highcharts/js/highcharts.js"></script>
	<script src="/Pie/Crust/Template/js/libs/jquery.cookie.js"></script>
</head> 
<body> 
	<div class="holder">
		<header>
			<div class="header">
				<div class="logo">
					<a href="<?=$homelink;?>"><img src="/Pie/Crust/Template/img/logo_white_hires_compressed.png" height="30" width="58" alt="StatusPeople" /></a>
				</div>
				<div class="menu">
					<?php
					if (!$logout)
					{
						echo '<ul class="fakericons">
						<li><a href="/Fakers/Dashboard"><span class="ico3 icon" data-tip="Fakers Dashboard">"</span> Dashboard</a></li>
						<li><a href="/Fakers/Followers"><span class="ico icon" data-tip="Follower Analytics">t</span> Analytics</a></li>
						<li><a href="" id="friendsearch"><span class="ico icon" data-tip="Search for Friends">s</span> Search</a></li>
						<li><a href="/Fakers/Help"><span class="ico3 icon" data-tip="Help">!</span> Help</a></li>
						<li><a href="http://statuspeople.com/Pages/Training" target="_blank"><span class="ico3 icon" data-tip="Training">)</span> Training</a></li>
						<li><a href="/Payments/Subscriptions"><span class="icon" data-tip="Purchase a Subscription">$</span> Subscriptions</a></li>	
						<li><a href="/Fakers/Settings"><span class="ico2 icon" data-tip="Settings">9</span> Settings</a></li>
						</ul>';	
					}
					else if ($logout == 1)
					{
						echo '<ul class="fakericons">
						<li><a href="/Fakers/Scores"><span class="ico3 icon" data-tip="Fakers Dashboard">"</span> Dashboard</a></li>
						<li><a href="/Fakers/Help"><span class="ico3 icon" data-tip="Help">!</span> Help</a></li>
						<li><a href="http://statuspeople.com/Pages/Training" target="_blank"><span class="ico3 icon" data-tip="Training">)</span> Training</a></li>
						<li><a href="/Payments/Subscriptions"><span class="icon" data-tip="Purchase a Subscription">$</span> Subscriptions</a></li>
						<li><a href="/Fakers/Settings"><span class="ico2 icon" data-tip="Settings">9</span> Settings</a></li>
						</ul>';
					}
					else if ($logout == 2)
					{
						echo '<ul class="fakericons">
						<li><a href="http://statuspeople.com/"><span class="ico icon" data-tip="Home">p</span> Home</a></li>
						<li><a href="/Fakers/Help"><span class="ico3 icon" data-tip="Help">!</span> Help</a></li>
						<li><a href="http://statuspeople.com/Pages/Training" target="_blank"><span class="ico3 icon" data-tip="Training">)</span> Training</a></li>
						<li><a href="http://blog.statuspeople.com"><span class="ico3 icon" data-tip="Blog">%</span> Blog</a></li>
						</ul>';
					}
					?>
					<input type="hidden" id="twitterhandle" value="<?=$twitterhandle;?>" />
					<input type="hidden" id="twitterid" value="<?=$twitterid;?>" />
					<input type="hidden" id="spamscore" value="0" />
					<input type="hidden" id="spam" value="0" />
					<input type="hidden" id="potential" value="0" />
					<input type="hidden" id="checks" value="0" />
					<input type="hidden" id="followers" value="0" />
					<input type="hidden" id="firsttime" value="<?=$firsttime;?>" />
					<input type="hidden" id="accounttype" value="<?=$type;?>"/>
				</div>
				<div class="connect">
					<?php
					// if ($logout == 1)
					// {
						// /*echo $menu;*/	
					// }
					// elseif ($logout!=1&&$logout!=2)
					// {
						echo '<ul><li class="accountform">'.$accountform.'</li></ul>';
					// }
					// elseif ($_SESSION['primaryid']!=$_SESSION['userid'])
					// {
						// echo '<ul><li class="accountform">'.$accountform.'</li></ul>';
					// }
					?>
				</div>
				<div class="selectMenu">
					<div></div>
					<div></div>
					<div></div>
				</div>
			</div>
			<?=$message;?>
		</header>