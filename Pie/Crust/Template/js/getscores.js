$(document).ready(function(){var g=$("#twitterid").val();var d=$("#twitterhandle").val();var c=new Server();var e=new Lengths();var h=new Popup();var b=new Messages();function f(){if($("#scoresholder").length){$("#scoresholder").remove()}if($("#shareform").length){$("#shareform").remove()}var j=$("<div/>");j.attr("id","scoresholder");j.addClass("row");j.insertBefore("#SearchForm")}function a(){var j=$("<div/>");j.attr("id","loader");j.html("<h1>Getting Faker Scores</h1>");j.attr("class","row connect center");var k=$("<img/>");k.attr("src","/Pie/Crust/Template/img/287.gif");k.attr("id","imageloader");k.appendTo(j);j.appendTo("#scoresholder")}function i(){$("#GetScoresForms").remove();f();a();var j=parseInt($.cookie("searches"));c.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+encodeURIComponent(g)+"&srch="+d+"&srchs="+j,"Spam_ProcessSpamData",1);c.CallServer("GET","json","/API/GetUserDetailsCount","rf=json&usr="+encodeURIComponent(g),"Spam_ProcessUserCheck")}$("#searchsubmit").bind("click",function(l){l.preventDefault();$(this).attr("disabled","disabled");var m=$("#searchquery").val();var k=e.StringLength(m);var j=parseInt($.cookie("searches"));if(j>0){if(k>0){f();a();$("#handle").text(m);c.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+encodeURIComponent(g)+"&srch="+m+"&srchs="+j,"Spam_ProcessSpamData",2)}else{b.Build("alert",["Please enter a Twitter username to search for."],".header");$(this).removeAttr("disabled")}}else{b.Build("alert",['You have no more friend searches left, please purchase a <a href="/Payments/Subscriptions">subscription</a> for unlimited searches.'],".header");$(this).removeAttr("disabled")}});$(document).on("click","#resetscores",function(j){j.preventDefault();f();a();$("#handle").text(d);$("#searchquery").val("");$("#searchquery").attr("placeholder","Twitter username...");c.CallServer("GET","json","/API/GetSpamScores","rf=json&usr="+encodeURIComponent(g)+"&srch="+d,"Spam_ProcessSpamData",1)});$(document).on("click","#popupclose",function(){h.RemovePopup()});$(document).on("click","#freesearches",function(k){k.preventDefault();h.BuildPopup();var j=$('<p>Fill in your details to get 5 Free Searches.</p><form><fieldset><label>Email:</label><input type="text" id="F5email"/></fieldset><fieldset><label>Title:</label><select id="F5title"><option value="Mr">Mr</option><option value="Mrs">Mrs</option><option value="Miss">Miss</option><option value="Ms">Ms</option><option value="Dr">Dr</option></select></fieldset><fieldset><label>First Name:</label><input type="text" id="F5fname"/></fieldset><fieldset><label>Last Name:</label><input type="text" id="F5lname"/></fieldset><fieldset><input type="submit" id="F5submit"/></fieldset></form>');h.Content(j)});$(document).on("click","#F5submit",function(l){l.preventDefault();var k=$("#F5email").val();var m=$("#F5title").val();var n=$("#F5fname").val();var j=$("#F5lname").val();h.TinyLoader();c.CallServer("POST","json","/API/PostAddUserDetails","rf=json&usr="+encodeURIComponent(g)+"&em="+k+"&tt="+m+"&fn="+n+"&ln="+j,"Spam_ProcessUserAddDetails",1)});$(document).on("click","#rightinfoclose",function(j){j.preventDefault();h.RightInfoClose()});$(document).on("click",".details",function(k){k.preventDefault();var j=$(this).attr("data-sc");h.Loader("Loading...");c.CallServer("GET","json","/API/GetTwitterUserData","rf=json&usr="+encodeURIComponent(g)+"&srch="+j,"Spam_BuildUser","")});$(document).on("click",".tweetfollowers",function(l){l.preventDefault();var j=$(this).attr("href");var k=j.split("//");var m=k[1].split("/");h.Loader("Loading...");c.CallServer("GET","json","/API/GetFollowerData","rf=json&usr="+encodeURIComponent(g)+"&ct=10&nm="+m[1],"Tweets_Followers")});$(document).on("click",".usertweettimeline",function(k){k.preventDefault();h.Loader("Loading...");h.Content("");var j=$(this).attr("href");var l=j.split("/");c.CallServer("GET","json","/API/GetUserTwitterTimeline","rf=json&usr="+encodeURIComponent(g)+"&srch="+l[3]+"&cnt=10","Tweets_BuildUserTimeline")});$(document).on("click","#gotopremium",function(j){j.preventDefault();window.location="/Payments/Subscriptions?type=2"});i()});