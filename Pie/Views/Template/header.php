<!doctype html> 
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]--> 
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]--> 
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]--> 
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]--> 
<head> 
    <title><?=$title;?></title> 
    <?php require_once($metaData); ?>
</head> 
<body> 
	<div class="holder">
		<header>
			<div class="header">
				<div class="logo">
					<a href="<?=$homeLink;?>"><img src="<?=$logo;?>" height="30" width="58" alt="StatusPeople" /></a>
				</div>
				<div class="menu">
					<?=$menu->build(); ?>
					<?=$hiddenFields->build();?>
				</div>
				<div class="connect">
					<?=$accountForm->build();?>
				</div>
				<div class="selectMenu">
					<div></div>
					<div></div>
					<div></div>
				</div>
			</div>
			<?=$messages;?>
		</header>