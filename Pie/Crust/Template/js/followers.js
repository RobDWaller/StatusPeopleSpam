$(document).ready(function(){var i=$("#twitterid").val();var h=$("#twitterid2").val();var d=$("#twitterhandle").val();var g=$("#accounttype").val();var b=new Server();var l=new Popup();var f=new Scroll();var e=new Lengths();function c(){b.CallServer("GET","json","/API/GetSpamScoresOverTime","rf=json&usr="+encodeURIComponent(h),"Charts_BuildChart",[{id:"chart",type:"line",multi:true,size:"large",xreverse:true,backgroundcolor:"#fefefe",colors:["#FE1B2A","#fe7d1d","#2AFE1B"]}])}function k(){b.CallServer("GET","json","/API/GetCacheData","rf=json&usr="+encodeURIComponent(h),"Spam_ProcessCacheData")}function j(){var o=$(".competitorlist tr").length;if(o>3){var m=0;$.each($(".competitorlist tr"),function(q,p){if(m>2){$(this).hide()}m+=1});var n=$('<tr><td colspan="7" class="center pointer blue" id="compmore" data-state="0">Show More</td></tr>');n.appendTo(".competitorlist tbody")}}$(document).on("click","#compmore",function(o){o.preventDefault();var n=parseInt($(this).attr("data-state"));if(n==0){$(".competitorlist tr").show();$(this).attr("data-state","1");$(this).text("Hide")}else{$(this).attr("data-state","0");$(this).text("Show More");var q=$(".competitorlist tr").length;if(q>3){var m=1;$.each($(".competitorlist tr"),function(s,r){if(m>3&&m<q){$(this).hide()}m+=1})}var p=new Scroll();p.To(".competitorlist",0,50)}});$(document).on("click",".delete",function(){var p=$(this).parent();var n=p.children("input");var o=n.val();var m=new Messages();m.DeleteCheck(o)});$(document).on("click","#deleteno",function(){l.RemovePopup()});$(document).on("click","#popupclose",function(){l.RemovePopup()});$(document).on("click","#deleteyes",function(){var m=$("#deletedata").val();l.RemovePopup();b.CallServer("POST","json","/API/PostDeleteFaker","rf=json&usr="+encodeURIComponent(i)+"&twid="+encodeURIComponent(m),"Spam_DeleteFaker",i)});$(document).on("click",".chart",function(){var q=$(this).parent();var o=q.children(".ti");var p=o.val();var n=q.children(".sc");var m=n.val();$("#charthandle").text(m);b.CallServer("GET","json","/API/GetSpamScoresOverTime","rf=json&usr="+encodeURIComponent(p),"Charts_BuildChart",[{id:"chart",type:"line",multi:true,size:"large",xreverse:true,backgroundcolor:"#fefefe",colors:["#FE1B2A","#fe7d1d","#2AFE1B"]}]);b.CallServer("GET","json","/API/GetCacheData","rf=json&usr="+encodeURIComponent(p),"Spam_ProcessCacheData");f.To("#charthandle",500,40)});$(document).on("click","#friendsearch",function(n){n.preventDefault();l.Loader();var m=$('<p>Search for a friend\'s faker score</p><form><fieldset><input type="text" id="friendsearchquery" /></fieldset><fieldset><input type="submit" value="Search" id="searchforfriend" /></fieldset></form>');l.Content(m)});$(document).on("click","#searchforfriend",function(n){n.preventDefault();var o=$("#friendsearchquery").val();var m=e.StringLength(o);if(m>0){l.TinyLoader();b.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+encodeURIComponent(i)+"&srch="+o+"&srchs=3","Spam_ProcessSpamDataPopup",i)}else{l.AddMessage("Please enter a Twitter username to search for",true)}});$(document).on("click","#addfakerpopup",function(q){q.preventDefault();var r=$("#friendsearchname").text();var p=$("#spam").val();var m=$("#potential").val();var n=$("#checks").val();var o=$("#followers").val();b.CallServer("POST","json","/API/PostAddFaker","rf=json&usr="+encodeURIComponent(i)+"&srch="+r+"&sp="+p+"&pt="+m+"&ch="+n+"&fl="+o,"Spam_AddFaker",i);l.RemovePopup();b.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+encodeURIComponent(i)+"&srch="+d,"Spam_ProcessSpamData",3)});$(document).on("click","#gotopremium",function(m){m.preventDefault();window.location="/Payments/Subscriptions?type=2"});$(document).on("click","#rightinfoclose",function(m){m.preventDefault();l.RightInfoClose()});$(document).on("click",".details",function(n){n.preventDefault();var m=$(this).attr("data-sc");l.Loader("Loading...");b.CallServer("GET","json","/API/GetTwitterUserData","rf=json&usr="+encodeURIComponent(i)+"&srch="+m,"Spam_BuildUser","")});$(document).on("click",".tweetfollowers",function(o){o.preventDefault();var m=$(this).attr("href");var n=m.split("//");var p=n[1].split("/");l.Loader("Loading...");b.CallServer("GET","json","/API/GetFollowerData","rf=json&usr="+encodeURIComponent(i)+"&ct=10&nm="+p[1],"Tweets_Followers")});$(document).on("click",".usertweettimeline",function(n){n.preventDefault();l.Loader("Loading...");l.Content("");var m=$(this).attr("href");var o=m.split("/");b.CallServer("GET","json","/API/GetUserTwitterTimeline","rf=json&usr="+encodeURIComponent(i)+"&srch="+o[3]+"&cnt=10","Tweets_BuildUserTimeline")});if(g==1){l.BuildRightInfoBox();var a=$('<p><strong>Auto Block</strong></p><p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p><form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');l.RightInfoContent(a)}j();c();k()});