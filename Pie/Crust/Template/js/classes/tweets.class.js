function Tweets(){this.BuildTweet=function(f){var c=$("<li/>");var b=$("<img/>");b.attr("src",f.avatar);b.attr("height","48px");b.attr("width","48px");b.appendTo(c);var e=$("<p/>");var a=this.HashAtLink(f.tweet);e.append("<strong>"+f.name+"</strong> ");e.append(a);e.appendTo(c);var d=$("<small/>");d.addClass("orange");d.html("Source: "+f.source+" | Date: "+f.date);d.appendTo(c);return c};this.HashAtLink=function(g){var f=new RegExp("https?:\\/\\/([a-zA-Z0-9\\/\\_\\-\\.\\(\\)\\&\\?\\=]*)","g");var e=new RegExp("^@([a-zA-Z0-9_-]{1,})","g");var d=new RegExp("\\s@([a-zA-Z0-9_-]{1,})","g");var c=new RegExp("^#([a-zA-Z0-9_-]{1,})","g");var b=new RegExp("\\s#([a-zA-Z0-9_-]{1,})","g");var a=g.replace(f,'<a href="http://$1" target="_blank" class="createdlink">$1</a>');a=a.replace(e,'<a href="http://twitter.com/$1" class="tweetuser" target="_blank">@$1</a>');a=a.replace(d,' <a href="http://twitter.com/$1" class="tweetuser" target="_blank">@$1</a>');a=a.replace(c,' <a href="http://twitter.com/search?q=%23$1" class="tweethash" target="_blank">#$1</a>');a=a.replace(b,' <a href="http://twitter.com/search?q=%23$1" class="tweethash" target="_blank">#$1</a>');return a};this.GetAtUsers=function(a){var c=new RegExp("\\s@([a-zA-Z0-9_-]{1,})","g");var b=a.match(c);return b};this.CreateRetweet=function(a){var e=new RegExp("^([a-zA-Z0-9_-]{1,})\\s");var d=new RegExp("([a-zA-Z0-9]{1,}\\.[a-zA-Z0-9]{1,}\\/[a-zA-Z0-9]{1,})","g");var c=a.match(e);var b="RT @"+c[1]+": ";var f=a.replace(e,b);f=f.replace(d,"http://$1");return f};this.BuildUser=function(i){var g=$("<li/>");var a=$("<div/>");var c=$("<img/>");c.attr("width","48");c.attr("height","48");c.attr("src",i.image);c.appendTo(a);var h=$("<p/>");h.html(i.screenname+"<br/>"+i.location+'<br/><a href="'+i.url+'" target="_blank">'+i.url+"</a><br/>Tweets: "+i.tweets+"<br/>Followers: "+i.followers+' <a href="http://twitter.com/'+i.screenname+'" class="tweetfollowers">View</a><br/>Friends: '+i.friends+"<br/>Days Active: "+i.daysactive);h.appendTo(a);var f=$("<p/>");f.attr("class","kred");f.appendTo(a);var b=new Server();b.CallServer("GET","json","/API/GetKredScore","rf=json&usr="+i.screenname,"Tweets_AddKredScore",f);var e=$("<p/>");e.text(i.description);e.appendTo(a);var d=$("<p/>");d.html('<a href="http://twitter.com/'+i.screenname+'" class="usertweettimeline">Timeline</a>');d.appendTo(a);a.appendTo(g);return g};this.BuildDM=function(a){};this.BuildRetweet=function(a){};this.AddKredScore=function(a,b){if(a.code==201){b.html('<img src="/Pie/Crust/Template/img/Kred_Logo.png" /> '+a.data.influence+"/"+a.data.outreach)}else{b.html('<img src="/Pie/Crust/Template/img/Kred_Logo.png" /> N/A')}};this.Timeline=function(j,a){var e=new Tweets();var g=new Hub();var b="#twitter"+a;var d="#timelineholder"+a;if(j.code==201){var h=0;if($(b).length){$.each(j.data,function(c,l){if(!h){$("#sinceid"+a).val(l.id)}var k=e.BuildTweet(l);k.prependTo(b);h=h+1})}else{var f=$("<ul/>");f.attr("id","twitter"+a);f.addClass("twittertimeline");$.each(j.data,function(c,l){if(!h){$("#sinceid"+a).val(l.id)}var k=e.BuildTweet(l);k.appendTo(f);h=h+1});f.appendTo(d)}}else{if($(b).length){}else{var i=new Popup();i.Loader();i.AddMessage("No data returned",true)}}if($("#viewnewtweets").length){$("#viewnewtweets").remove()}g.Loader("")};this.Followers=function(a){var c=new Popup();var b=new Tweets();if(a.code==201){var d=$("<ul/>");d.addClass("twitterusers");$.each(a.data,function(f,e){var g=b.BuildUser(e);g.appendTo(d)});c.Content(d,"popupscroll")}else{c.AddMessage("No follower data returned at this time.",true)}c.RemoveLoader()};this.NewCount=function(a){if(a.code==201){var b=new Hub();var e=0;$.each(a.data,function(c,f){e+=1});if($("#viewnewtweets").length){$("#viewnewtweets").text("You have "+e+" new Tweets to view.")}else{var d=$("<div/>");d.attr("id","viewnewtweets");d.text("You have "+e+" new Tweets to view.");d.insertBefore("#timelineholderhome")}}b.Loader("")};this.BuildUserTimeline=function(a){var c=new Tweets();var b=new Popup();b.RemoveLoader();b.RemoveMessage();if(a.code==201){var d=$("<ul/>");$.each(a.data,function(f,e){var g=c.BuildTweet(e);g.prependTo(d)});b.Content(d)}else{b.AddMessage("No tweets found for this user",true)}}};