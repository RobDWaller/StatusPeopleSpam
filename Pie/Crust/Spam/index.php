<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
<?=$message;?>
<div class="columnholder bglblue2">
    <div class="banner">
        <ul>
            <li class="bannerimage"></li>   
            <li class="bannertext bree"><a href="/" class="whitelink nounderline">Fake Follower Check</a></li>
        </ul>
    </div>
    <div class="column row bgwhite">
        <div class="one">
            <div class="content center">
                <p>Find out how many fake followers you and your friends have.</p>
                <div class="row connect">
                    <p class="bree sp2"><img src="/Pie/Crust/Template/img/twitter-bird-light-bgs.png" height="126" /> <form action="/Fakers/AuthenticateTwitter" method="post" id="connectform"><input type="hidden" name="ru" value="http://fakers.statuspeople.com/Fakers/TwitterSuccess" /><input type="submit" value="Connect to Twitter" /></form></p>
                </div>
            </div>
        </div>
        <div class="row one center">
            <small><a href="/Fakers/Terms/" target="_blank">Terms</a> | <a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | <a href="http://tools.statuspeople.com" target="_blank">Sign Up For a StatusPeople Account</a> | <a href="http://eepurl.com/mveWD" target="_blank">Sign Up To Our Email Newsletter</a></small>
        </div>
        <div class="row one">
            <h2>Fakers List</h2>
            <?=$spamrecords;?>
            <div class="one textright"><p class="sm1"><a href="/Fakers/Wall">See more...</a></p></div>
        </div>
    </div>
</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>