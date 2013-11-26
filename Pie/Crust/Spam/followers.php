<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree">@<span id="handle"><?=$twitterhandle;?></span> Followers</li>
			</ul>
		</div>
		<div class="column">
			<div class="one">
				<div class="content">
					<div class="center">
						<?=$scores;?>
						<input type="hidden" id="twitterhandle" value="<?=$twitterhandle;?>" />
						<input type="hidden" id="twitterid" value="<?=$twitterid;?>" />
						<input type="hidden" id="twitterid2" value="<?=$twitterid2;?>" />
						<input type="hidden" id="spamscore" value="0" />
						<input type="hidden" id="spam" value="0" />
						<input type="hidden" id="potential" value="0" />
						<input type="hidden" id="checks" value="0" />
						<input type="hidden" id="followers" value="0" />
						<input type="hidden" id="firsttime" value="<?=$firsttime;?>" />
						<input type="hidden" id="accounttype" value="<?=$type;?>"/>
						<!--<input type="hidden" id="firsttime" value="1" />-->
					</div>
					<div class="row">
						<div class="one" id="fakerslist">
							<h2>Friend's List</h2>
							<?=$competitors;?>
						</div>
					</div>
					<div class="row">
						<div class="twoma a">
							<h2>Language Stats</h2>
						</div>
						<div class="twomb">
							<h2><span id="charttitle">@<span id="charthandle"><?=$twitterhandle;?></span>'s Fakers Chart</span></h2>
						</div>
						<div class="twoma a" id="langchart">
							
						</div>
						<div class="twomb" id="chart">
						</div>
					</div>
					<div class="row one">
						<h2>
							Your Followers
						</h2>
						<div id="followerdata">
						</div>
					</div> 
					<div class="row">
						<div class="one center">
							<small><a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | <a href="/Payments/Details">Extend Your Subscription</a> | <a href="/Fakers/Reset">I'm having problems accessing data</a></small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<img src="/Pie/Crust/Template/img/287.gif" style="display:none;" />
<script src="/Pie/Crust/Template/js/classes/spam.class.js"></script>
<script src="/Pie/Crust/Template/js/sharescores.js"></script>
<script src="/Pie/Crust/Template/js/followers.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>