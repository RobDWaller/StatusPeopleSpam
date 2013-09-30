<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree">@<span id="handle"><?=$twitterhandle;?></span> Fakers Dashboard</li>
			</ul>
		</div>
		<div class="column">
			<div class="one">
				<div class="content">
					<div class="center">
						<?=$scores;?>
						<input type="hidden" id="twitterhandle" value="<?=$twitterhandle;?>" />
						<input type="hidden" id="twitterid" value="<?=$twitterid;?>" />
						<input type="hidden" id="spamscore" value="0" />
						<input type="hidden" id="spam" value="0" />
						<input type="hidden" id="potential" value="0" />
						<input type="hidden" id="checks" value="0" />
						<input type="hidden" id="followers" value="0" />
						<input type="hidden" id="firsttime" value="<?=$firsttime;?>" />
						<!--<input type="hidden" id="firsttime" value="1" />-->
						<div class="row connect one" id="GetScoresForms">
							<p class="bree sp2"><form action="/Fakers/GetScores" method="post" id="myscoreform"><input type="hidden" name="name" value="<?=$twitterhandle;?>" /><input type="submit" value="Get Faker Scores" /></form></p>
						</div>
						<div class="row connect" id="SearchForm">
							<p>Search for friend's or business rival's and add them to your own fakers list.</p>
							<p class="bree sp2"><form action="/Fakers/GetScores" method="post" id="searchform"><input type="text" name="name" id="searchquery" placeholder="Twitter username..." /><input type="submit" value="Search" id="searchsubmit" /></form></p>
						</div>
					</div>
					<?php if($_SESSION['type']==2){?>
						<div class="row">
							<div class="two a">
								<h2>
									Auto Remove Spam
								</h2>
								<?=$autoremoveform;?>
							</div>
							<div class="two">
								<h2>
									Your Deep Dive Score
								</h2>
								<?=$rundeepdiveform;?>
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="twoma a">
							<h2>Fake Followers</h2>
						</div>
						<div class="twomb">
							<h2><span id="charttitle">@<span id="charthandle"><?=$twitterhandle;?></span>'s Fakers Chart</span></h2>
						</div>
						<div class="twoma a" id="spammers">
							<?=$fakes;?>
						</div>
						<div class="twomb" id="chart">
						</div>
					</div>
					<div class="row">
						<div class="one" id="fakerslist">
							<h2>Fakers List</h2>
							<?=$competitors;?>
						</div>
					</div>
					<div class="row">
						<div class="one center">
							<small><a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | <a href="http://tools.statuspeople.com" target="_blank">Sign Up For a StatusPeople Account</a> | <a href="http://eepurl.com/mveWD" target="_blank">Sign Up To Our Email Newsletter</a> | <a href="/Payments/Details">Extend Your Subscription</a> | <a href="/Fakers/Reset">I'm having problems accessing data</a></small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<img src="/Pie/Crust/Template/img/287.gif" style="display:none;" />
<script src="/Pie/Crust/Template/js/classes/spam.class.js"></script>
<script src="/Pie/Crust/Template/js/sharescores.js"></script>
<script src="/Pie/Crust/Template/js/advanced.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>