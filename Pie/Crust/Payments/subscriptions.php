<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">Subscriptions</a></li>
			</ul>
		</div>
		<div class="column row bgwhite">
			<div class="content row">
				<div class="two a">
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
					<small>Remember to read our <a href="http://tools.statuspeople.com/User/Terms" target="_blank">terms and conditions</a> before you buy.</small>
				</div>
				<div class="two">
					<h2>Details</h2>
					<p>Get 1 month free when you buy a 6 month subscription and 2 if you buy 12.</p>
					<?=$form;?>
				</div>
			</div>
			<div class="row">
				<div class="one">
					<table>
						<tr>
							<th>Account</th>
							<th>Unlimited Searches</th>
							<th>Manual Block Fakes</th>
							<th>Advanced Follower Metrics</th>
							<th>Tracked Accounts</th>
							<th>Auto Block Fakes</th>
						</tr>
						<tr>
							<td><strong class="orange">Basic</strong></td>
							<td class="ico green center">;</td>
							<td class="ico green center">;</td>
							<td class="ico green center">;</td>
							<td class="center"><strong class="orange">5</strong></td>
							<td class="ico red center">9</td>
						</tr>
						<tr>
							<td><strong class="orange">Premium</strong></td>
							<td class="ico green center">;</td>
							<td class="ico green center">;</td>
							<td class="ico green center">;</td>
							<td class="center"><strong class="orange">15</strong></td>
							<td class="ico green center">;</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="column row bg2">
			<div class="row one">
				<h2 class="white">Want to see what you'll get for your money?</h2>
			</div>
			<div class="row">
				<div class="two a center"><a href="/Pie/Crust/Template/img/fakers_dboard_1.png" target="_blank"><img src="/Pie/Crust/Template/img/fakers_dboard_1.png" width="300px" height="318px" style="float:none; border:1px solid #fefefe;" /></a></div>
				<div class="two center"><a href="/Pie/Crust/Template/img/fakers_dboard_2.png" target="_blank"><img src="/Pie/Crust/Template/img/fakers_dboard_2.png" width="300px" height="318px" style="float:none; border:1px solid #fefefe;" /></a></div>
			</div>
		</div>
	</div>
<script src="/Pie/Crust/Template/js/classes/spam.class.js"></script>
<script src="/Pie/Crust/Template/js/payments.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>