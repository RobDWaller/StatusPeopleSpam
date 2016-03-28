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
			<table>
				<thead>
					<tr>
						<th>Avatar</th>
						<th>Screen Name</th>
						<th>Email</th>
						<th>Name</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><a href="https://twitter.com/<?=$user->first()->screen_name;?>" target="_blank"><img src="<?=$user->first()->avatar;?>" height="48px" width="48px" /></a></td>
						<td><?=$user->first()->screen_name;?></td>
						<td><?=$user->first()->email;?></td>
						<td><?=$user->first()->title;?> <?=$user->first()->forename;?> <?=$user->first()->surname;?></td>
						<td><a href="/Accounts/Loader?id=<?=$hash->encode($user->first()->twitterid);?>">View Account</a></td>
					</tr>
				</tbody>
			</table>
			<h2>Add Purchase</h2>
			<?=$form->build();?>
			<h2>Purchases</h2>
			<table>
				<thead>
					<tr>
						<th>Transaction ID</th>
						<th>Amount</th>
						<th>Type</th>
						<th>Complete</th>
						<th>Valid Until</th>
						<th>Created</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($purchases as $p) { ?>
						<tr>
							<td><?=$p->transactionid;?></td>
							<td><?=Fakers\UnitConverter::currencyStringToType($p->currency);?><?=$p->amount;?></td>
							<td><?=Fakers\UnitConverter::accountTypeToString($p->type);?></td>
							<td><?=Fakers\UnitConverter::booleanToString($p->complete);?> </td>
							<td><?=Fakers\UnitConverter::timestampToDate($p->valid);?></td>
							<td><?=Fakers\UnitConverter::timestampToDate($p->created);?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php require_once($footerPath); ?>