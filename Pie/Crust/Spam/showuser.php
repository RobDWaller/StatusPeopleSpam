<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">@<?=$screen_name;?> Faker Score</a></li>
			</ul>
		</div>
		<div class="column bgwhite">
			<div class="one">
				<div class="content">
					<div class="center">
						<div id="scoresholder" class="row">
							<div class="three a red">
								<h1 class="red">Fake</h1><h2 class="red"><?=$fake;?>%</h2>
							</div>
							<div class="three a">
								<h1>Inactive</h1><h2><?=$inactive;?>%</h2>
							</div>
							<div class="three">
								<h1 class="green">Good</h1><h2 class="green"><?=$good;?>%</h2>
							</div>
						</div>
						<div class="row">
							<div class="one">
								<h2>
									Followers: <?=$followers;?> | Score Date: <?=$date;?>
								</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>