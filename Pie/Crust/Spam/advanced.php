<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
<?=$message;?>
<div class="columnholder bglblue2">
    <div class="banner">
        <ul>
            <li class="bannerimage"></li>   
            <li class="bannertext bree">@<span id="handle"><?=$twitterhandle;?></span> Fakers Dashboard</li>
        </ul>
    </div>
    <div class="column bgwhite">
        <div class="one">
            <div class="content">
                <div class="center">
                    <?=$scores;?>
                    <input type="hidden" id="twitterhandle" value="<?=$twitterhandle;?>" />
                    <input type="hidden" id="twitterid" value="<?=$twitterid;?>" />
                    <input type="hidden" id="spamscore" value="0" />
                    <input type="hidden" id="spam" value="0" />
                    <input type="hidden" id="potential" value="0" />
                    <input type="hidden" id="checks" value="0" />
                    <input type="hidden" id="followers" value="0" />
                    <input type="hidden" id="firsttime" value="<?=$firsttime;?>" />
                    <div class="row connect one" id="GetScoresForms">
                        <p class="bree sp2"><form action="/Fakers/GetScores" method="post" id="myscoreform"><input type="hidden" name="name" value="<?=$twitterhandle;?>" /><input type="submit" value="Get Faker Scores" /></form></p>
                    </div>
                    <div class="row connect" id="SearchForm">
                        <p>Search for friend's or business rival's and add them to your own fakers list.</p>
                        <p class="bree sp2"><form action="/Fakers/GetScores" method="post" id="searchform"><input type="text" name="name" id="searchquery" placeholder="Twitter username..." /><input type="submit" value="Search" id="searchsubmit" /></form></p>
                    </div>
                </div>
                <div class="row">
                    <div class="twoa a">
                        <h2>Fake Followers</h2>
                    </div>
                    <div class="twob">
                        <h2><span id="charttitle">@<span id="charthandle"><?=$twitterhandle;?></span>'s Fakers Chart</span></h2>
                    </div>
                    <div class="twoa a" id="spammers">
                        <?=$fakes;?>
                    </div>
                    <div class="twob" id="chart">
                    </div>
                </div>
                <div class="row">
                    <div class="one" id="fakerslist">
                        <h2>Fakers List</h2>
                        <?=$competitors;?>
                    </div>
                </div>
                <div class="row">
                    <div class="one center">
                        <small><a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | <a href="http://tools.statuspeople.com" target="_blank">Sign Up For a StatusPeople Account</a> | <a href="http://eepurl.com/mveWD" target="_blank">Sign Up To Our Email Newsletter</a> | <a href="/Payments/Details">Extend Your Subscription</a> | <a href="/Fakers/Reset">I'm having problems accessing data</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<img src="/Pie/Crust/Template/img/287.gif" style="display:none;" />
<script src="/Pie/Crust/Template/js/highcharts/js/highcharts.js"></script>
<script src="/Pie/Crust/Template/js/classes/length.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/server.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/spam.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/charts.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/scroll.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/popup.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/tweets.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/messages.class.js"></script>
<script src="/Pie/Crust/Template/js/sharescores.js"></script>
<script src="/Pie/Crust/Template/js/advanced.js"></script>
<script src="/Pie/Crust/Template/js/closemessages.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>