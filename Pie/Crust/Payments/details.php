<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
<?=$message;?>
<div class="columnholder bglblue2">
    <div class="banner">
        <ul>
            <li class="bannerimage"></li>   
            <li class="bannertext bree"><a href="/" class="whitelink nounderline">Your Details</a></li>
        </ul>
    </div>
    <div class="column row bgwhite">
        <div class="content row one">
            <p>Please fill in your details to proceed to payment.</p>
            <?=$form;?>
        </div>
    </div>
</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>