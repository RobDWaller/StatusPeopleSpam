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
		</div>
	</div>
	<div class="row">
		<div class="two">
			<h2>Recent Sign Ups</h2>
			<table>
				<thead>
					<tr>
						<th>Avatar</th>
						<th>Screen Name</th>
						<th>Sign Up Date</th>
					</tr>
				<thead>
				<tbody>
					<?php foreach ($newUsers as $nu) { ?>
						<tr>
							<td><a href="https://twitter.com/<?=$nu->screen_name;?>" target="_blank"><img src="<?=$nu->avatar;?>" width="48px" height="48px" /></a></td>
							<td><a href="/Account/User?id=<?=$hash->encode($nu->twitterid);?>"><?=$nu->screen_name;?></a></td>
							<td><?=Fakers\UnitConverter::timestampToDate($nu->created);?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="two">
			<h2>Recent Blocks</h2>
			<table>
				<thead>
					<tr>
						<th>Blocked Last Week</th>
						<th>Blocked Last Month</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?=Fakers\UnitConverter::formatNumber($block_week_count);?></td>
						<td><?=Fakers\UnitConverter::formatNumber($block_month_count);?></td>
					</tr>
				</tbody>
			</table>
			<table>
				<thead>
					<tr>
						<th>Avatar</th>
						<th>Screen Name</th>
						<th>Created</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($blocks as $b) { ?>
						<tr>
							<td><a href="https://twitter.com/<?=$b->screen_name;?>" target="_blank"><img src="<?=$b->avatar;?>" height="48px" width="48px" /></a></td>
							<td><?=$b->screen_name;?></td>
							<td><?=Fakers\UnitConverter::timestampToDate($b->created);?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php require_once($footerPath); ?>