$(document).ready(function(){var h=$("#twitterid").val();var d=$("#twitterhandle").val();var b=new Server();var i=new Popup();var g=new Scroll();var e=new Lengths();function c(){b.CallServer("GET","json","/API/GetSpamScoresOverTime","rf=json&usr="+h,"Charts_BuildChart")}function f(){if($("#scoresholder").length){$("#scoresholder").remove()}if($("#shareform").length){$("#shareform").remove()}var k=$("<div/>");k.attr("id","scoresholder");k.addClass("row");k.insertBefore("#SearchForm")}function a(n,m){var k=$("<div/>");k.attr("id","loader");k.html("<h1>"+n+"</h1>");k.attr("class","row connect center");var l=$("<img/>");l.attr("src","/Pie/Crust/Template/img/287.gif");l.attr("id","imageloader");l.appendTo(k);k.appendTo(m)}function j(){$("#GetScoresForms").remove();f();a("Getting Faker Scores","#scoresholder");var k=parseInt($("#firsttime").val());if(k==1){b.CallServer("GET","json","/API/GetSpamRecords","rf=json&usr="+h+"&srch="+d,"Spam_ProcessSpamDataFirstTime",h)}else{b.CallServer("GET","json","/API/GetCachedSpamScore","rf=json&usr="+h,"Spam_ProcessCachedSpamData",1)}}$("#searchsubmit").bind("click",function(l){l.preventDefault();$(this).attr("disabled","disabled");var m=$("#searchquery").val();var k=e.StringLength(m);if(k>0){f();a("Getting Faker Scores","#scoresholder");b.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+h+"&srch="+m,"Spam_ProcessSpamDataAdvanced",h)}else{i.Loader();i.AddMessage("Please enter a Twitter username to search for.",true);$(this).removeAttr("disabled")}});$(document).on("click","#resetscores",function(k){k.preventDefault();f();a("Getting Faker Scores","#scoresholder");$("#handle").text(d);$("#searchquery").val("");$("#searchquery").attr("placeholder","Twitter username...");b.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+h+"&srch="+d,"Spam_ProcessSpamData",1)});$(document).on("click","#addfaker",function(o){o.preventDefault();var p=$("#searchquery").val();var n=$("#spam").val();var k=$("#potential").val();var l=$("#checks").val();var m=$("#followers").val();b.CallServer("POST","json","/API/PostAddFaker","rf=json&usr="+h+"&srch="+p+"&sp="+n+"&pt="+k+"&ch="+l+"&fl="+m,"Spam_AddFaker",h);f();a("Getting Faker Scores","#scoresholder");$("#handle").text(d);$("#searchquery").val("");$("#searchquery").attr("placeholder","Twitter username...");b.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+h+"&srch="+d,"Spam_ProcessSpamData",1)});$(document).on("click",".delete",function(){var n=$(this).parent();var l=n.children("input");var m=l.val();var k=new Messages();k.DeleteCheck(m)});$(document).on("click","#deleteno",function(){i.RemovePopup()});$(document).on("click","#popupclose",function(){i.RemovePopup()});$(document).on("click","#deleteyes",function(){var k=$("#deletedata").val();i.RemovePopup();b.CallServer("POST","json","/API/PostDeleteFaker","rf=json&usr="+h+"&twid="+k,"Spam_DeleteFaker",h)});$(document).on("click",".chart",function(){var p=$(this).parent();var m=p.children(".ti");var o=m.val();var l=p.children(".sc");var k=l.val();$("#charthandle").text(k);b.CallServer("GET","json","/API/GetSpamScoresOverTime","rf=json&usr="+o,"Charts_BuildChart");g.To("#charthandle",500,10);if(!$("#chartreset").length){var n=$('<span id="chartreset"/>');n.text("Reset");n.insertAfter("#charttitle")}});$(document).on("click",".details",function(n){n.preventDefault();var k=$(this).parent().parent();var l=k.children(".sc");var m=l.val();i.Loader("Loading...");b.CallServer("GET","json","/API/GetTwitterUserData","rf=json&usr="+h+"&srch="+m,"Spam_BuildUser","")});$(document).on("click",".block",function(n){n.preventDefault();var k=$(this).parent().parent();var l=k.children(".ti");var m=l.val();b.CallServer("POST","json","/API/PostBlockSpam","rf=json&usr="+h+"&twid="+m,"Spam_BlockUser",h)});$(document).on("click",".notspam",function(n){n.preventDefault();var k=$(this).parent().parent();var l=k.children(".ti");var m=l.val();b.CallServer("POST","json","/API/PostNotSpam","rf=json&usr="+h+"&twid="+m,"Spam_NotSpam",h)});$(document).on("click",".tweetfollowers",function(m){m.preventDefault();var k=$(this).attr("href");var l=k.split("//");var n=l[1].split("/");i.Loader("Loading...");b.CallServer("GET","json","/API/GetFollowerData","rf=json&usr="+h+"&ct=10&nm="+n[1],"Tweets_Followers")});$(document).on("click",".usertweettimeline",function(l){l.preventDefault();i.Loader("Loading...");i.Content("");var k=$(this).attr("href");var m=k.split("/");b.CallServer("GET","json","/API/GetUserTwitterTimeline","rf=json&usr="+h+"&srch="+m[3]+"&cnt=10","Tweets_BuildUserTimeline")});$(document).on("click","#chartreset",function(){$("#charthandle").text(d);b.CallServer("GET","json","/API/GetSpamScoresOverTime","rf=json&usr="+h,"Charts_BuildChart");g.To("#charthandle",500,10);$(this).fadeOut(1000,function(){$(this).remove()})});$(document).on("click","#checkfakes",function(){if($("#checkform").length){$("#checkform").remove()}a("Checking For New Fake Followers","#spammers");b.CallServer("GET","json","/API/GetUpdateFakersList","rf=json&usr="+h+"&srch="+d,"Spam_UpdateFakersList",h)});j();c()});