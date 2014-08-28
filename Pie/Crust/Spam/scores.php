<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
		<div class="column">
			<div class="banner">
				<ul>
					<li class="bannerimage"></li>   
					<li class="bannertext bree">@<span id="handle"><?=$twitterhandle;?></span> Faker Scores</li>
				</ul>
			</div>
			<?=$scores;?>
			<div class="one content center" id="GetScoresForms">
				<form action="/Fakers/GetScores" method="post" id="myscoreform">
				<fieldset><input type="hidden" name="name" value="<?=$twitterhandle;?>" />
				<input type="submit" value="Get Faker Scores" /></fieldset>
				</form>
			</div>
			<div class="connect one center top bottom" id="SearchForm">
				<small><a href="/<?=$twitterhandle;?>">View Your Faker Page</a></small>
				<h3>How Many Fake Followers Do Your Friends Have?</h3>
				<form action="/Fakers/GetScores" method="post" id="searchform">
				<fieldset><input type="text" name="name" id="searchquery" placeholder="Twitter username..." /></fieldset>
				<fieldset>
				<input type="submit" value="Search" id="searchsubmit" /></fieldset></form>
				<h3><span id="friendsearches"><?=$searches;?></span> Friend Searche(s) Left.</h3>
			</div>
			<div class="one blue top">
				<h2 class="white">Upgrade To Fakers Dashboard</h2>
			</div>
			<div class="two pricing box center blue">			
				<h2>Basic</h2>
				<h3>&pound;3.49 &#36;5.49 &euro;4.49</h3>
				<small>Per Month</small>
				<p>
					Unlimited Searches
				</p>
				<p>Block Spam</p>
				<p>Track 5 Accounts</p>
				<p>
					Advanced Follower Metrics
				</p>
			</div>
			<div class="two center pricingborders blue">
				<h2>Premium</h2>
				<h3>&pound;9.99 &#36;14.99 &euro;12.99</h3>
				<small>Per Month</small>
				<p>
					Unlimited Searches
				</p>
				<p>Auto Block Spam</p>
				<p>Track 15 Accounts</p>
				<p>
					Advanced Follower Metrics
				</p>
			</div>									
			<div class="one">
				<h2 class="white">Purchase a Subscription: Your Details</h2>
			</div>
			<div class="one">
				<?=$form;?>
			</div>
			<div class="one">
				<h2>Want to see what you'll get for your money?</h2>
			</div>
			<div class="two a center"><a href="/Pie/Crust/Template/img/fakers_dboard_1.png" target="_blank"><img src="/Pie/Crust/Template/img/fakers_dboard_1.png" width="300px" height="318px" style="float:none; border:1px solid #fefefe;" /></a></div>
			<div class="two center"><a href="/Pie/Crust/Template/img/fakers_dboard_2.png" target="_blank"><img src="/Pie/Crust/Template/img/fakers_dboard_2.png" width="300px" height="318px" style="float:none; border:1px solid #fefefe;" /></a></div>
			<div class="one center">
				<small><a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | <a href="http://statuspeople.com/Pages/Training" target="_blank">Learn More, Join Our Training Webinars</a> | <a href="/Fakers/Reset">I'm having problems accessing data</a></small>
			</div>
		</div>
<img src="/Pie/Crust/Template/img/287.gif" style="display:none;" />
<script src="/Pie/Crust/Template/minify/js/getscores.min.js"></script>
<script src="/Pie/Crust/Template/minify/js/sharescores.min.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>