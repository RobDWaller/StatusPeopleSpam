<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
<?=$message;?>
<div class="columnholder bglblue2">
    <div class="banner">
        <ul>
            <li class="bannerimage"></li>   
            <li class="bannertext bree">@<span id="handle"><?=$twitterhandle;?></span> Faker Scores</li>
        </ul>
    </div>
    <div class="column bgwhite">
        <div class="content">
            <div class="row center">
                <?=$scores;?>
                <input type="hidden" id="twitterhandle" value="<?=$twitterhandle;?>" />
                <input type="hidden" id="twitterid" value="<?=$twitterid;?>" />
                <input type="hidden" id="spamscore" value="0" />
                <div class="row connect" id="GetScoresForms">
                    <p class="bree sp2"><form action="/Fakers/GetScores" method="post" id="myscoreform"><input type="hidden" name="name" value="<?=$twitterhandle;?>" /><input type="submit" value="Get Faker Scores" /></form></p>
                </div>
                <div class="row connect" id="SearchForm">
                    <p>Find out how many fake followers your friends have.</p>
                    <p class="bree sp2"><form action="/Fakers/GetScores" method="post" id="searchform"><input type="text" name="name" id="searchquery" placeholder="Twitter username..." /><input type="submit" value="Search" id="searchsubmit" /></form></p>
                </div>
                <div class="row one center">
                    <small><a href="/Fakers/FindOutMore/" target="_blank">Find out more...</a> | <a href="http://tools.statuspeople.com" target="_blank">Sign Up For a StatusPeople Account</a> | <a href="http://eepurl.com/mveWD" target="_blank">Sign Up To Our Email Newsletter</a> | <a href="/Fakers/Reset">I'm having problems accessing data</a></small>
                </div>
            </div>
            <div class="row bgorange">
                <div class="row">
                    <div class="two a">
                        <h2 class="white">Upgrade To Fakers Dashboard</h2>
                    </div>
                    <div class="two">
                        <h2 class="white">Your Details</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="two a">
                        <div class="pricingbox">
                            <div class="three center">
                                <h2 class="blue">UK</h2>
                                <h1>£4.99*</h1>
                                <small class="orange bree">Per Month</small>
                                <p class="bree blue">Block Spam</p>
                                <p class="bree blue">Track 5 Accounts</p>
                            </div>
                            <div class="three center pricingborders">
                                <h2 class="blue">US</h2>
                                <h1>&#36;8.99*</h1>
                                <small class="orange bree">Per Month</small>
                                <p class="bree blue">Block Spam</p>
                                <p class="bree blue">Track 5 Accounts</p>
                            </div>
                            <div class="three center">
                                <h2 class="blue">EU</h2>
                                <h1>&euro;6.99*</h1>
                                <small class="orange bree">Per Month</small>
                                <p class="bree blue">Block Spam</p>
                                <p class="bree blue">Track 5 Accounts</p>
                            </div>
                        </div>
                        <small class="white">* Excludes VAT of 20%. Please read our <a href="http://tools.statuspeople.com/User/Terms" class="whitelink" target="_blank">terms and conditions</a> carefully before you purchase. All payments taken via Pay Pal.</small>
                    </div>
                    <div class="two">
                        <?=$form;?>
                    </div>
                    <div class="row one">
                        <h2 class="white">Want to see what you'll get for your money? <a href="http://tools.statuspeople.com/Images/View/V/nnnxg" class="whitelink" target="_blank">Take a look...</a></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<img src="/Pie/Crust/Template/img/287.gif" style="display:none;" />
<script src="/Pie/Crust/Template/js/classes/popup.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/length.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/server.class.js"></script>
<script src="/Pie/Crust/Template/js/classes/spam.class.js"></script>
<script src="/Pie/Crust/Template/js/getscores.js"></script>
<script src="/Pie/Crust/Template/js/sharescores.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>