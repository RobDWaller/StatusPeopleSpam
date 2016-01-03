<?php require_once($headerPath); ?>
<div class="column">
	<div class="banner">
		<ul>
			<li class="bannerimage"></li>   
			<li class="bannertext bree"><a href="/" class="whitelink nounderline"><?=$title;?></a></li>
		</ul>
	</div>
	<div class="one">
		<div class="content">
			<h2>Details</h2>
			<ul>
				<li><?=$user->first()->screen_name;?></li>
				<li style="width:50px;"><img src="<?=$user->first()->avatar;?>" /></li>
				<li><?=$user->first()->email;?></li>
				<li><?=$user->first()->title;?></li>
				<li><?=$user->first()->forename;?></li>
				<li><?=$user->first()->surname;?></li>
			</ul>
			<a href="/Accounts/Loader?id=<?=$hash->encode($user->first()->twitterid);?>">View Account</a>
			<h2>Purchases</h2>
			<ul>
				<?php foreach($purchases as $p) { ?>
					<li>
						<?=$p->created;?> <?=$p->transactionid;?> <?=$p->currency;?><?=$p->amount;?>
						<?=$p->type;?> <?=$p->complete;?> <?=$p->valid;?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<?php require_once($footerPath); ?>