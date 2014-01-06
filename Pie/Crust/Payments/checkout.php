<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">Checkout</a></li>
			</ul>
		</div>
		<div class="column row bgwhite">
			<div class="content row">
				<div class="one">
					<p>All payments are taken via PayPal. Please be aware that to complete the transaction you must return to our site after paying. This will usually occur automatically, if not please click one of the links provided by PayPal.</p>
					<h2>Cart</h2>
					<table id="checkoutcart">
						<tr>
							<td>Account Type:</td><td><span id="type"><?=$type;?></span></td>
						</tr>
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
					<div class="textright">
						<small id="termstext"><input type="checkbox" id="tc"/> I have read and agree to the StatusPeople.com <a href="/Fakers/Terms" target="_blank">terms and conditions</a>.</small>
					</div>
					<?=$form;?>
				</div>
			</div>
		</div>
	</div>
<script src="/Pie/Crust/Template/js/classes/spam.class.js"></script>
<script src="/Pie/Crust/Template/js/payments.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>