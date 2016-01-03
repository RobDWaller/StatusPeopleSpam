<?php require_once($headerPath); ?>
<div class="column">
	<div class="banner">
		<ul>
			<li class="bannerimage"></li>   
			<li class="bannertext bree"><a href="/Admin/Login" class="whitelink nounderline">Admin Login</a></li>
		</ul>
	</div>
	<div class="one">
		<div class="content">
			<?=$form->build();?>
		</div>
	</div>
</div>
<?php require_once($footerPath); ?>