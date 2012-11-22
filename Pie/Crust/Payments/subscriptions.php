<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
<?=$message;?>
<div class="columnholder bglblue2">
    <div class="banner">
        <ul>
            <li class="bannerimage"></li>   
            <li class="bannertext bree"><a href="/" class="whitelink nounderline">Subscriptions</a></li>
        </ul>
    </div>
    <div class="column row bgwhite">
        <div class="content row">
            <div class="two a">
                <h2>Cart</h2>
                <table id="cart">
                    <tr>
                        <td>Subscription Period:</td><td><span id="cartmonths"><?=$months;?></span> Month(s)</td>
                    </tr>
                    <tr>
                        <td>Sub-Total:</td><td><span class="currency"><?=$currency;?></span><span id="cartsubtotal"><?=$subtotal;?></span></td>
                    </tr>
                    <tr>
                        <td>Saving:</td><td><span class="currency"><?=$currency;?></span>-<span id="cartsaving"><?=$saving;?></span></td>
                    </tr>
                    <tr>
                        <td>Tax:</td><td><span class="currency"><?=$currency;?></span><span id="carttax"><?=$tax;?></span></td>
                    </tr>
                    <tr>
                        <td><strong>Total:</strong></td><td><strong><span class="currency"><?=$currency;?></span><span id="carttotal"><?=$total;?></span></strong></td>
                    </tr>
                </table>
                <small>Remember to read our <a href="http://tools.statuspeople.com/User/Terms" target="_blank">terms and conditions</a> before you buy.</small>
            </div>
            <div class="two">
                <h2>Details</h2>
                <p>Get 1 month free when you buy a 6 month subscription and 2 if you buy 12.</p>
                <?=$form;?>
            </div>
        </div>
    </div>
</div>
<script src="/Pie/Crust/Template/js/classes/payments.class.js"></script>
<script src="/Pie/Crust/Template/js/payments.js"></script>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>