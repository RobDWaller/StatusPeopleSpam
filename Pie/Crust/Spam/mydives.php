<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
		<div class="column">
			<div class="banner">
				<ul>
					<li class="bannerimage"></li>   
					<li class="bannertext bree"><a href="/" class="whitelink nounderline">Deep Dive Admin Scores</a></li>
				</ul>
			</div>			
			<div class="one">
				<div class="content">
					<h2>Add a Deep Dive</h2>
					<?=$form->build();?>
					<?=$dives;?>
				</div>
			</div>
		</div>
<script src="/Pie/Crust/Template/minify/js/sitesadmin.min.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>