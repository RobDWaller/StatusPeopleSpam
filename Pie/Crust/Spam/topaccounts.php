<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
		<div class="column">
			<div class="banner">
				<ul>
					<li class="bannerimage"></li>   
					<li class="bannertext bree"><a href="/Fakers/Celebs" class="whitelink nounderline">Celebs</a></li>
				</ul>
			</div>
			<?php foreach ($topScores as $c => $tS) { ?>
				<div class="three<?php echo ($c<3?' content':''); ?>">
					<a href="/<?=$tS['screen_name']?>" class="pageLink">
						<img src="<?=$tS['avatar']?>" />
						@<?=$tS['screen_name']?><br/>
						<small>Followers: <?=number_format($tS['followers']);?> <span><?=round(($tS['spam']/$tS['checks'])*100);?>% Fake</span></small>
					</a>
				</div>
			<?php } ?>
		</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>