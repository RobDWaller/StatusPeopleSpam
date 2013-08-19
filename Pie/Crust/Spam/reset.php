<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree">Reset Connection Details</li>
			</ul>
		</div>
		<div class="column bgwhite">
			<div class="one">
				<div class="content">
					<p>Occasionally our Fakers App experiences problems connecting your account to the Twitter API. 
						When this occurs we generally advise that you reset your connection details and then reconnect 
						to our tool. This usually resolves any problems you have been experiencing, but if not please contact 
						info@statuspeople.com.
					</p>
					<p><fieldset><a href="/Fakers/ResetConnectionDetails"><input type="button" value="Reset Now" id="reset" /></a></fieldset></p>
				</div>
			</div>
		</div>
	</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>