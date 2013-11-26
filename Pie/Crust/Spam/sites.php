<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">Faker Sites</a></li>
			</ul>
		</div>
		<div class="column row bgwhite">
			<div class="content row one">
				<p>
					Do you know of a site that sells fake followers, likes or views? Let us know by submitting the website URL below. We will collate them all 
					so we can keep our coummunity informed on who the sellers and frauds are.
				</p>
				<form id="fakersiteform">
					<fieldset>
						<input type="text" id="fakersite" class="icon" data-tip="Please include http:// or https://" value=""/>
					</fieldset>
					<fieldset>
						<input type="submit" id="fakersitesubmit" value="Submit A Faker Site URL"/>
					</fieldset>
				</form>
			</div>
			<div class="row one">
				<h2>
					The Faker Site List
				</h2>
				<p>
					These sites sell fake followers and other fake marketing metrics. We suggest you keep away.
				</p>
				<?=$sites;?>
			</div>
		</div>
	</div>
<script src="/Pie/Crust/Template/js/classes/spam.class.js"></script>
<script src="/Pie/Crust/Template/js/sites.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>