<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree">@<span id="handle"><?=$twitterhandle;?></span> Fakers</li>
			</ul>
		</div>
		<div class="column">
			<div class="one">
				<div class="content">
					<div class="center">
						<?=$scores;?>
						<!--<input type="hidden" id="firsttime" value="1" />-->
						<div class="row connect one" id="GetScoresForms">
							<p class="bree sp2"><form action="/Fakers/GetScores" method="post" id="myscoreform"><input type="hidden" name="name" value="<?=$twitterhandle;?>" /><input type="submit" value="Get Faker Scores" /></form></p>
						</div>
						<div class="row connect" id="SearchForm">
							<p>Search for friend's or business rival's and add them to your own fakers list.</p>
							<p class="bree sp2"><form action="/Fakers/GetScores" method="post" id="searchform"><input type="text" name="name" id="searchquery" placeholder="Twitter username..." /><input type="submit" value="Search" id="searchsubmit" /></form></p>
						</div>
					</div>
					<div class="row">
						<div class="two a">
							<h2>Fake Followers</h2>
						</div>
						<div class="two">
							<h2>
								Blocked (<span id="blockcount"><?=$blockcount?></span>)<span id="searchblocked" class="ico icon blue pointer" data-tip="Search Blocked Followers">s</span>
							</h2>
						</div>
						<div class="two a" id="spammers">
							<?=$fakes;?>
						</div>
						<div class="two" id="blocked">
							<?=$blocked;?>
						</div>
					</div>
					<?php if($type==2){?>
						<div class="row" id="autoblock">
							<div class="two">
								<h2>
									Auto Block Fakes <?php echo ($autoon?'<span class="green">On <span class="ico">;</span></span> <span id="autooff" class="microbutton pointer">Turn Off</span>':'<span class="red">Off <span class="ico" style="font-size:20px;">9</span></span> <span id="autoon" class="microbutton pointer">Turn On</span>'); ?>
								</h2>
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="one center">
							<small><a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | <a href="/Payments/Details">Extend Your Subscription</a> | <a href="http://statuspeople.com/Pages/Training" target="_blank">Learn More, Join Our Training Webinars</a> | <a href="/Fakers/Reset">I'm having problems accessing data</a></small>
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