<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">Settings</a></li>
			</ul>
		</div>
		<div class="column bgwhite">
			<div class="one">
				<div class="content">
					<h2>
						Your Details
					</h2>
					<?=$form;?>
					<?php if ($type==3) {?>
					<h2>
						Manage Sub Accounts
					</h2>
					<?=$accounts;?>
					<h2>
						Your API Key
					</h2>
					<p>
						<?=$apikey;?>
					</p>
					<p>
						<a href="/Fakers/ResetAPI">Reset API Key</a>
					</p>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>