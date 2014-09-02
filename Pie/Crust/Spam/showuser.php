<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
		<div class="column">
			<div class="banner">
				<ul>
					<li class="bannerimage"></li>   
					<li class="bannertext bree"><a href="/<?=$screen_name;?>" class="whitelink nounderline">@<?=$screen_name;?> Faker Page</a></li>
				</ul>
			</div>
			<div id="scoresholder">
				<div class="three content center">
					<h1 class="red">Fake</h1><h2 class="red"><?=$fake;?>%</h2>
				</div>
				<div class="three content center">
					<h1>Inactive</h1><h2><?=$inactive;?>%</h2>
				</div>
				<div class="three content center">
					<h1 class="green">Good</h1><h2 class="green"><?=$good;?>%</h2>
				</div>
			</div>
			<div class="one center">
				<h2 class="showuser">
					<a href="http://twitter.com/<?=$screen_name;?>" target="_blank"><img src="<?=$avatar;?>" /> <span>@<?=$screen_name;?></span><br/>
					Followers: <?=$followers;?></a><br/>
				</h2>
				<small><?=$date;?></small>
			</div>
			<div class="one">
				<h2>Popular Pages</h2>
			</div>
			<?php foreach ($topScores as $tS) { ?>
				<div class="three">
					<a href="/<?=$tS['screen_name']?>" class="pageLink">
						<img src="<?=$tS['avatar']?>" />
						@<?=$tS['screen_name']?><br/>
						<small>Followers: <?=number_format($tS['followers']);?> <span><?=($tS['checks']==0?'0':round(($tS['spam']/$tS['checks'])*100));?>% Fake</span></small>
					</a>
				</div>
			<?php } ?>
			<div class="one">
				<a href="/Fakers/Celebs">View More...</a>
			</div>
		</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>