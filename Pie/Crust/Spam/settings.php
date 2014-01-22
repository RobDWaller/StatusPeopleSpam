<?php require_once(__SITE_PATH.'/Pie/Crust/Template/header.php'); ?>
	<div class="columnholder bg5">
		<div class="banner">
			<ul>
				<li class="bannerimage"></li>   
				<li class="bannertext bree"><a href="/" class="whitelink nounderline">Settings</a></li>
			</ul>
		</div>
		<div class="column bgwhite">
			<div class="one">
				<div class="content">
					<h2>
						Your Details
					</h2>
					<?=$form;?>
					<?php if ($type==3&&$nosettings!=1) {?>
					<h2>
						Manage Sub Accounts
					</h2>
					<?=$accounts;?>
					<h2>
						Your API Key
					</h2>
					<p>
						<?=$apikey;?>
					</p>
					<p>
						<a href="/Fakers/ResetAPI">Reset API Key</a>
					</p>
					<h2>
						Example Request
					</h2>
					<code>
						<pre>
type = GET
url = http://fakers.statuspeople.com/API/GetAPIScore

variables...

ky = API Key
rf = Response Format = json || xml
sc = Twitter Screen Name

Output XML...
&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;response&gt;
	&lt;code&gt;201&lt;/code&gt;
	&lt;message&gt;Request Successful, Twitter User Data Found.&lt;/message&gt;
	&lt;data&gt;
		&lt;screen_name&gt;RobDWaller&lt;/screen_name&gt;
		&lt;avatar&gt;http://a0.twimg.com/profile_images/1204287499/PixelMe_normal.png&lt;/avatar&gt;
		&lt;fake&gt;0&lt;/fake&gt;
		&lt;inactive&gt;31&lt;/inactive&gt;
		&lt;good&gt;69&lt;/good&gt;
		&lt;followers&gt;885&lt;/followers&gt;
		&lt;deepdive&gt;No&lt;/deepdive&gt;
		&lt;timestamp&gt;1387200671&lt;/timestamp&gt;
		&lt;date&gt;2013/12/16 13:31&lt;/date&gt;
	&lt;/data&gt;
&lt;/response&gt;

Output JSON...
{
	"code":201,
	"message":"Request Successful, Twitter User Data Found.",
	"data":
	{
		"screen_name":"RobDWaller",
		"avatar":"http:\/\/a0.twimg.com\/profile_images\/1204287499\/PixelMe_normal.png",
		"fake":0,
		"inactive":31,
		"good":69,
		"followers":"885",
		"deepdive":"No",
		"timestamp":"1387200671",
		"date":"2013\/12\/16 13:31"
	}
}
						</pre>
					</code>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php require_once(__SITE_PATH.'/Pie/Crust/Template/footer.php'); ?>