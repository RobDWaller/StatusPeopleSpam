<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
		<div class="column">
			<div class="banner">
				<ul>
					<li class="bannerimage"></li>   
					<li class="bannertext bree"><a href="/" class="whitelink nounderline">@<?=$screen_name;?> Faker Score</a></li>
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
				<h2>
					Followers: <?=$followers;?> | Score Date: <?=$date;?>
				</h2>
			</div>
		</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>