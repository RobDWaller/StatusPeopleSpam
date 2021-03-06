<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="column">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">Subscriptions</a></li>
			</ul>
		</div>
		<?php if ($_SESSION['primaryid'] == $_SESSION['userid']) { ?>
		<div class="one content table">
			<h2>Subscription Types</h2>
				<table id="subscriptiontypes">
					<tr>
						<th>&nbsp;</th>
						<th>Basic</th>
						<th>Premium</th>
						<th>Agency</th>
					</tr>
					<tr>
						<td><strong class="orange">Unlimited Searches</strong></td>
						<td class="ico green center">;</td>
						<td class="ico green center">;</td>
						<td class="ico green center">;</td>
					</tr>
					<tr>
						<td><strong class="orange">Manual Block Fakes</strong></td>
						<td class="ico green center">;</td>
						<td class="ico green center">;</td>
						<td class="ico green center">;</td>
					</tr>
					<tr>
						<td><strong class="orange">Follower Analytics</strong></td>
						<td class="ico green center">;</td>
						<td class="ico green center">;</td>
						<td class="ico green center">;</td>
					</tr>
					<tr>
						<td><strong class="orange">Tracked Accounts</strong></td>
						<td class="center"><strong class="orange">5</strong></td>
						<td class="center"><strong class="orange">15</strong></td>
						<td class="center"><strong class="orange">30</strong></td>
					</tr>
					<tr>
						<td><strong class="orange">Auto Block Fakes</strong></td>
						<td class="ico red center">9</td>
						<td class="ico green center">;</td>
						<td class="ico green center">;</td>
					</tr>
					<tr>
						<td><strong class="orange">Multi-Account Management</strong></td>
						<td class="ico red center">9</td>
						<td class="ico red center">9</td>
						<td class="ico green center">;</td>
					</tr>
					<tr>
						<td><strong class="orange">API Access</strong></td>
						<td class="ico red center">9</td>
						<td class="ico red center">9</td>
						<td class="ico green center">;</td>
					</tr>
				</table>
		</div>
		<div class="two">
			<h2>Purchase Details</h2>
			<p>Get 1 month free when you buy a 6 month subscription and 2 if you buy 12.</p>
			<?=$form;?>
		</div>
		<div class="two table">
			<input type="hidden" id="subtype" value="<?=$type;?>"/>
			<h2>Cart</h2>
			<table id="cart">
				<tr>
					<td>Subscription Period:</td><td><span id="cartmonths"><?=$months;?></span> Month(s)</td>
				</tr>
				<tr>
					<td>Sub-Total:</td><td><span class="currency"><?=$currency;?></span><span id="cartsubtotal"><?=$subtotal;?></span></td>
				</tr>
				<tr>
					<td>Saving:</td><td><span class="currency"><?=$currency;?></span>-<span id="cartsaving"><?=$saving;?></span></td>
				</tr>
				<tr>
					<td>Tax:</td><td><span class="currency"><?=$currency;?></span><span id="carttax"><?=$tax;?></span></td>
				</tr>
				<tr>
					<td><strong>Total:</strong></td><td><strong><span class="currency"><?=$currency;?></span><span id="carttotal"><?=$total;?></span></strong></td>
				</tr>
			</table>
			<small>Remember to read our <a href="/Fakers/Terms" target="_blank">terms and conditions</a> before you buy.</small>
		</div>
		<div class="one">
			<h2>Want to see what you'll get for your money?</h2>
		</div>
		<div class="two">
			<a href="/Pie/Crust/Template/img/fakers_dboard_1.png" target="_blank">
				<img src="/Pie/Crust/Template/img/fakers_dboard_1.png" />
			</a>
		</div>
		<div class="two">
			<a href="/Pie/Crust/Template/img/fakers_dboard_2.png" target="_blank">
				<img src="/Pie/Crust/Template/img/fakers_dboard_2.png" />
			</a>
		</div>
		<div class="one center">
			<small>
					<a href="/Fakers/Terms/" target="_blank">Terms</a> | 
					<a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | 
					<a href="//statuspeople.com/Pages/Training" target="_blank">Learn More, Join Our Training Webinars</a>
				</small>
		</div>
		<?php } else { ?>
			<div class="one content">
				<p>You cannot purchase a Subscription for a Sub Account. Please connect to this account directly if you wish to purchase a subscription for it.</p>
			</div>
		<?php } ?>
	</div>
<script src="/Pie/Crust/Template/minify/js/payments.min.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>