$(document).ready(function(){var i=$("#twitterid").val();var h=$("#twitterid2").val();var d=$("#twitterhandle").val();var g=$("#accounttype").val();var b=new Server();var k=new Popup();var f=new Scroll();var e=new Lengths();function c(){b.CallServer("GET","json","/API/GetSpamScoresOverTime","rf=json&usr="+encodeURIComponent(h),"Charts_BuildChart",[{id:"chart",type:"line",multi:true,size:"large",xreverse:true,backgroundcolor:"#fefefe",colors:["#FE1B2A","#fe7d1d","#2AFE1B"]}])}function j(){b.CallServer("GET","json","/API/GetCacheData","rf=json&usr="+encodeURIComponent(h),"Spam_ProcessCacheData")}$(document).on("click",".delete",function(){var o=$(this).parent();var m=o.children("input");var n=m.val();var l=new Messages();l.DeleteCheck(n)});$(document).on("click","#deleteno",function(){k.RemovePopup()});$(document).on("click","#popupclose",function(){k.RemovePopup()});$(document).on("click","#deleteyes",function(){var l=$("#deletedata").val();k.RemovePopup();b.CallServer("POST","json","/API/PostDeleteFaker","rf=json&usr="+encodeURIComponent(i)+"&twid="+encodeURIComponent(l),"Spam_DeleteFaker",i)});$(document).on("click",".chart",function(){var p=$(this).parent();var n=p.children(".ti");var o=n.val();var m=p.children(".sc");var l=m.val();$("#charthandle").text(l);b.CallServer("GET","json","/API/GetSpamScoresOverTime","rf=json&usr="+encodeURIComponent(o),"Charts_BuildChart",[{id:"chart",type:"line",multi:true,size:"large",xreverse:true,backgroundcolor:"#fefefe",colors:["#FE1B2A","#fe7d1d","#2AFE1B"]}]);b.CallServer("GET","json","/API/GetCacheData","rf=json&usr="+encodeURIComponent(o),"Spam_ProcessCacheData");f.To("#charthandle",500,40)});$(document).on("click","#friendsearch",function(m){m.preventDefault();k.Loader();var l=$('<p>Search for a friend\'s faker score</p><form><fieldset><input type="text" id="friendsearchquery" /></fieldset><fieldset><input type="submit" value="Search" id="searchforfriend" /></fieldset></form>');k.Content(l)});$(document).on("click","#searchforfriend",function(m){m.preventDefault();var n=$("#friendsearchquery").val();var l=e.StringLength(n);if(l>0){k.TinyLoader();b.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+encodeURIComponent(i)+"&srch="+n+"&srchs=3","Spam_ProcessSpamDataPopup",i)}else{k.AddMessage("Please enter a Twitter username to search for",true)}});$(document).on("click","#addfakerpopup",function(p){p.preventDefault();var q=$("#friendsearchname").text();var o=$("#spam").val();var l=$("#potential").val();var m=$("#checks").val();var n=$("#followers").val();b.CallServer("POST","json","/API/PostAddFaker","rf=json&usr="+encodeURIComponent(i)+"&srch="+q+"&sp="+o+"&pt="+l+"&ch="+m+"&fl="+n,"Spam_AddFaker",i);k.RemovePopup();b.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+encodeURIComponent(i)+"&srch="+d,"Spam_ProcessSpamData",3)});$(document).on("click","#gotopremium",function(l){l.preventDefault();window.location="/Payments/Subscriptions?type=2"});$(document).on("click","#rightinfoclose",function(l){l.preventDefault();k.RightInfoClose()});$(document).on("click",".details",function(m){m.preventDefault();var l=$(this).attr("data-sc");k.Loader("Loading...");b.CallServer("GET","json","/API/GetTwitterUserData","rf=json&usr="+encodeURIComponent(i)+"&srch="+l,"Spam_BuildUser","")});$(document).on("click",".tweetfollowers",function(n){n.preventDefault();var l=$(this).attr("href");var m=l.split("//");var o=m[1].split("/");k.Loader("Loading...");b.CallServer("GET","json","/API/GetFollowerData","rf=json&usr="+encodeURIComponent(i)+"&ct=10&nm="+o[1],"Tweets_Followers")});$(document).on("click",".usertweettimeline",function(m){m.preventDefault();k.Loader("Loading...");k.Content("");var l=$(this).attr("href");var n=l.split("/");b.CallServer("GET","json","/API/GetUserTwitterTimeline","rf=json&usr="+encodeURIComponent(i)+"&srch="+n[3]+"&cnt=10","Tweets_BuildUserTimeline")});if(g==1){k.BuildRightInfoBox();var a=$('<p><strong>Auto Block</strong></p><p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p><form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');k.RightInfoContent(a)}c();j()});