<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
		<div class="column">
			<div class="banner">
				<ul>
					<li class="bannerimage"></li>   
					<li class="bannertext sf2"><a href="/" >Fake Follower Check</a></li>
				</ul>
			</div>
			<div class="one top">
				<div class="content center">
					<h2>Find out how many fake followers you and your friends have</h2>
					<div class="connect show">
							<form action="/Fakers/AuthenticateTwitter" method="post" id="connectform">
								<input type="hidden" name="ru" value="http://fakers.statuspeople.com/Fakers/TwitterSuccess" />
								<img src="/Pie/Crust/Template/img/twitter-bird-light-bgs.png" alt="Twitter Connect" />
								<input type="submit" value="Connect to Twitter" />
							</form>
					</div>
				</div>
			</div>
			<div class="one center bottom">
				<small>
					<a href="/Fakers/Terms/" target="_blank">Terms</a> | 
					<a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | 
					<a href="http://statuspeople.com/Pages/Training" target="_blank">Learn More, Join Our Training Webinars</a>
				</small>
			</div>
			<div class="one"><h2>Fakers List</h2></div>
			<?=$spamrecords;?>
			<div class="one"><p class="sm1"><a href="/Fakers/Wall">See more...</a></p></div>
		</div>
<script>
	$(document).ready(function(){
		var srv = new Server();
		srv.CallServer('GET','json','/API/GetEventbriteData','rf=json','Spam_EventbriteData');
	});
</script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>