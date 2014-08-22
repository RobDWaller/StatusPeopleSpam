<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="column">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">Your Details</a></li>
			</ul>
		</div>
			<div class="content one">
				<?php if ($_SESSION['primaryid'] == $_SESSION['userid']) { ?>
				<p>Please fill in your details to proceed to payment.</p>
				<?=$form;?>
				<?php } else { ?>
				<p>You cannot purchase a Subscription for a Sub Account. Please connect to this account directly if you wish to purchase a subscription for it.</p>
				<?php } ?>
			</div>
	</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>