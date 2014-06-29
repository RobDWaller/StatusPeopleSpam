<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
		<div class="column">
			<div class="banner">
				<ul>
					<li class="bannerimage"></li>   
					<li class="bannertext bree">@<span id="handle"><?=$twitterhandle;?></span> Fakers</li>
				</ul>
			</div>
			<div class="one">
				<div class="content">
						<?=$scores;?>
						<!--<input type="hidden" id="firsttime" value="1" />-->
						<div class="connect" id="GetScoresForms">
							<p><form action="/Fakers/GetScores" method="post" id="myscoreform"><input type="hidden" name="name" value="<?=$twitterhandle;?>" /><input type="submit" value="Get Faker Scores" /></form></p>
						</div>
				</div>
			</div>
			<div class="one top bottom center" id="SearchForm">
				<h2>Search for friend's or business rival's and add them to your own fakers list</h2>
				<form action="/Fakers/GetScores" method="post" id="searchform">
					<fieldset>
						<input type="text" name="name" id="searchquery" placeholder="Twitter username..." />
					</fieldset>
					<fieldset>
						<input type="submit" value="Search" id="searchsubmit" />
					</fieldset>
				</form>
			</div>
			<?php if($type>=2){?>
					<div class="one" id="autoblock">
						<?php echo ($autoon?'<h2>Auto Block Fakes <span class="green">On <span class="ico">;</span></span></h2> <span id="autooff" class="microbutton pointer">Turn Off</span>':'<h2>Auto Block Fakes <span class="red">Off <span class="ico" style="font-size:20px;">9</span></span></h2> <span id="autoon" class="microbutton pointer">Turn On</span>'); ?>
					</div>
			<?php } ?>
			<div class="two" id="spammers">
				<h2>Fake Followers</h2>
				<?=$fakes;?>
			</div>
			<div class="two" id="blocked">
				<h2>
					Blocked (<span id="blockcount"><?=$blockcount?></span>)<span id="searchblocked" class="ico icon pointer" data-tip="Search Blocked Followers">s</span>
				</h2>
				<?=$blocked;?>
			</div>
			<div class="one center">
				<small><a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | <a href="/Payments/Details">Extend Your Subscription</a> | <a href="http://statuspeople.com/Pages/Training" target="_blank">Learn More, Join Our Training Webinars</a> | <a href="/Fakers/Reset">I'm having problems accessing data</a></small>
			</div>
		</div>
<img src="/Pie/Crust/Template/img/287.gif" style="display:none;" />
<script src="/Pie/Crust/Template/js/classes/spam.class.js"></script>
<script src="/Pie/Crust/Template/js/sharescores.js"></script>
<script src="/Pie/Crust/Template/js/advanced.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>