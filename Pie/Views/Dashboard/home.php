<?php require_once($headerPath); ?>
<div class="column">
	<div class="banner">
		<ul>
			<li class="bannerimage"></li>   
			<li class="bannertext bree"><a href="/" class="whitelink nounderline">Dashboard</a></li>
		</ul>
	</div>
	<div class="one">
		<div class="content">
			<h2>Search</h2>
			<?=$form->build();?>
			<h2>Recent Sign Ups</h2>
			<ul>
				<?php foreach ($newUsers as $nu) { ?>
					<li><a href="/Accounts/User?id=<?=$hash->encode($nu->twitterid);?>"><?=$nu->screen_name;?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<?php require_once($footerPath); ?>