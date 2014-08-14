<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="column">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">Confirmation</a></li>
			</ul>
		</div>
			<div class="content one table">
					<p>Thank you for purchasing a Fakers Dashboard subscription. You can start using your <a href="/Fakers/Dashboard">Fakers Dashboard</a> now.</p>
					<p>We have sent you an email confirming all your details. Including your purchase id which you should reference in any communication with us about your purchase.</p>
					<h2>Purchase ID: <?=$transactionid?></h2>
					<p>If you need to speak to us about your purchase please email info@statuspeople.com.</p>
					<h2>Details</h2>
					<table id="checkoutcart">
						<tr>
							<td>Type:</td><td><span id="type"><?=$type;?></span></td>
						</tr>
						<tr>
							<td>Subscription Period:</td><td><span id="cartmonths"><?=$months;?></span> Month(s)</td>
						</tr>
						<tr>
							<td>Sub-Total:</td><td><span class="currency"><?=$currency;?></span> <span id="cartsubtotal"><?=$subtotal;?></span></td>
						</tr>
						<tr>
							<td>Saving:</td><td><span class="currency"><?=$currency;?></span> -<span id="cartsaving"><?=$saving;?></span></td>
						</tr>
						<tr>
							<td>Tax:</td><td><span class="currency"><?=$currency;?></span> <span id="carttax"><?=$tax;?></span></td>
						</tr>
						<tr>
							<td><strong>Total:</strong></td><td><strong><span class="currency"><?=$currency;?></span> <span id="carttotal"><?=$total;?></span></strong></td>
						</tr>
					</table>
					<p>Go to your <a href="/Fakers/Dashboard">Fakers Dashboard</a> now.</p>
			</div>
	</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>