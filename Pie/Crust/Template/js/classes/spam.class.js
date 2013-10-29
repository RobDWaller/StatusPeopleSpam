function Charts(){this.Chart=function(c,b,d){options={chart:{renderTo:b[0].id,defaultSeriesType:b[0].type,height:b[0].height,backgroundColor:b[0].backgroundcolor,zoomType:b[0].zoom},title:{text:b[0].title,x:0,align:"center",style:{color:"#fe7d1d",fontSize:"16px"}},subtitle:{text:b[0].subtitle,x:0,style:{color:"#36b6d5"}},xAxis:{title:{text:b[0].xtitle,style:{color:"#444"}},categories:[],labels:{enabled:b[0].xenabled},reversed:b[0].xreverse},yAxis:{title:{text:b[0].ytitle,style:{color:"#36b6d5"}},plotLines:[{value:0,width:1}],min:b[0].ymin,offset:b[0].yoffset,showFirstLabel:b[0].yfirstlabel},tooltip:{enabled:true,formatter:function(){if(this.series.chart.options.chart.defaultSeriesType=="pie"){return"<b>"+this.point.name+":</b> "+Highcharts.numberFormat(this.percentage,1)+"%"}else{if(this.series.chart.options.chart.defaultSeriesType=="column"){return"Hour: "+this.x+" | Count: "+this.y}else{return this.series.name+": "+this.y}}}},legend:{enabled:b[0].legendenabled,borderWidth:0},plotOptions:{pie:{showInLegend:true,dataLabels:{enabled:false}},column:{stacking:"normal"}},colors:b[0].colors,series:[]};if(b[0].multi&&(b[0].type=="line"||b[0].type=="column")){$.each(c.data,function(g,f){var h={data:[]};h.name=g;$.each(f,function(j,k){options.xAxis.categories.push(k.date);h.data.push(parseFloat(k.count))});options.series.push(h)})}else{if(b[0].type=="pie"){options.series=[{type:"pie",name:b[0].xtitle}];var e={data:[]};$.each(c.data,function(g,f){e.data.push({name:f.name,y:parseFloat(f.count)})});options.series.push(e)}else{var e={data:[]};e.name=b[0].interactions;$.each(c.data,function(g,f){options.xAxis.categories.push(f.date);e.data.push(parseFloat(f.count))});options.series.push(e)}}var a=new Highcharts.Chart(options);if(d){this.ExportButton(d)}};this.BuildChart=function(a,c){if(a.code==201){var b;if(c[0].size=="small"){b=[{id:c[0].id,type:c[0].type,multi:c[0].multi,height:200,backgroundcolor:c[0].backgroundcolor,zoom:"",title:"",subtitle:"",xtitle:"",xreverse:c[0].xreverse,xenabled:c[0].xenabled,ytitle:"",ymin:"",yoffset:-40,yfirstlabel:false,legendenabled:false,interactions:c[0].interactions,colors:c[0].colors}]}else{b=[{id:c[0].id,type:c[0].type,multi:c[0].multi,height:null,backgroundcolor:c[0].backgroundcolor,zoom:"x",title:c[0].title,subtitle:c[0].subtitle,xtitle:"Date",xreverse:c[0].xreverse,xenabled:true,ytitle:"",ymin:"",yoffset:"",yfirstlabel:true,legendenabled:true,interactions:c[0].interactions,colors:c[0].colors}]}this.Chart(a,b,c[1])}else{$("#"+c[0].id).html("<p>No Data Returned</p>")}}}function Lengths(){this.GetLength=function(b,a,f){var e=0;var h=0;if(f){var d=new RegExp("http:\\/\\/([a-zA-Z0-9\\/\\_\\-\\.\\(\\)\\&\\?\\=\\%\\#\\!]*)","g");var c=b.match(d);if(c){$.each(c,function(k,j){e+=j.length;h+=25})}}var g=b.length;g=g-e;g=g+h;g=a-g;return g};this.StringLength=function(a){return a.length}}function Messages(){this.Build=function(j,a,b){if($("#"+j+"message").length){$("#"+j+"message").remove()}var h=$("<ul/>");$.each(a,function(n,l){var k=$("<li/>");k.html(l);k.appendTo(h)});var g=$('<div class="'+j+'message bree" id="'+j+'message"/>');var f=$('<div class="e1"/>');f.html('<img src="/Pie/Crust/Template/img/'+j+'_icon.png" />');f.appendTo(g);var e=$('<div class="e2 s0"/>');h.appendTo(e);e.appendTo(g);var c=$('<div class="e3 s0 '+j+'close"/>');c.html('<span id="'+j+'close">X</span>');c.appendTo(g);g.insertAfter(b);function i(){var k=new Messages();k.CloseMessages(["#"+j+"message"])}var d=setTimeout(i,15000)};this.DeleteCheck=function(g){var c=new Popup();c.Loader();c.AddMessage("Are You Sure?");var f=$("<div/>");var b=$('<input id="deletedata" type="hidden"/>');b.val(g);b.appendTo(f);var e=$('<ul class="nav"/>');var d=$('<li id="deleteyes" class="microbutton pointer"/>');d.text("Yes");d.appendTo(e);var a=$('<li id="deleteno" class="microbutton pointer"/>');a.text("No");a.appendTo(e);e.appendTo(f);c.Content(f)};this.CloseMessages=function(a){$.each(a,function(c,b){if($(b).length){$(b).fadeOut("slow",function(){$(this).remove()})}})}}function Payments(){this.RoundNumber=function(b,a){var c=Math.round(b*Math.pow(10,a))/Math.pow(10,a);return parseFloat(c)};this.RecalculateCart=function(i,b,h){var k="&pound;";var a=3.49;var c=0.7;if(h==2){a=9.99;c=1.98}if(i=="USD"){k="&#36;";a=5.49;c=0;if(h==2){a=14.99;c=0}}else{if(i=="EUR"){k="&euro;";a=4.49;c=0.9;if(h==2){a=12.99;c=2.6}}}var d=1;var l=0;if(b==6){d=5;l=1}else{if(b==12){d=10;l=2}}var e=new Payments();var m=e.RoundNumber((d*a),2);var j=e.RoundNumber((l*a),2);var f=e.RoundNumber((d*c),2);var g=e.RoundNumber((m+f),2);$(".currency").html(k);$("#cartmonths").text(b);$("#cartsubtotal").text(m.toFixed(2));$("#cartsaving").text(j.toFixed(2));$("#carttax").text(f.toFixed(2));$("#carttotal").text(g.toFixed(2));$("#subtotal").val(m.toFixed(2));$("#tax").val(f.toFixed(2));$("#months").val(b);$("#saving").val(j.toFixed(2))}}function Popup(){this.BuildPopup=function(e){var c=$('<div id="loaderfade2"/>');var d=$("<div/>");d.attr("id","popup");var f=$("<div/>");f.attr("id","popupcloseholder");var a=$("<a/>");a.attr("id","popupclose");a.text("Close");a.appendTo(f);f.appendTo(d);var b=$("<div/>");b.attr("id","popupdata");b.append(e);b.appendTo(d);d.appendTo(c);c.appendTo("body")};this.Loader=function(b){var a=$("<div/>");a.attr("id","popuploader");a.text(b);if($("#popup").length>0){a.prependTo("#popupdata")}else{this.BuildPopup(a)}};this.Content=function(c,a){if($("#popupcontent").length>0){$("#popupcontent").remove()}var b=$("<div/>");b.attr("id","popupcontent");b.append(c);if(a){b.addClass(a)}b.appendTo("#popupdata")};this.AddContent=function(b,a){b.appendTo("#popupcontent")};this.AddMessage=function(c,b){var a=$("<div/>");if(b){a.addClass("persistanterror")}else{a.addClass("persistantsuccess")}a.attr("id","popupmessage");a.text(c);a.prependTo("#popupdata")};this.RemovePopup=function(){$("#loaderfade2").remove()};this.RemoveContent=function(){$("#popupcontent").remove()};this.RemoveLoader=function(){$("#popuploader").remove()};this.RemoveMessage=function(){$("#popupmessage").remove()};this.TinyLoader=function(){if(!$("#tinyloader").length){var b=$('<div id="loaderfade"/>');var a=$('<div id="tinyloader"><h2>Loading...</h2><img src="http://fakers.statuspeople.com/Pie/Crust/Template/img/287.gif" /></div>');a.appendTo(b);b.appendTo("body")}};this.RemoveTinyLoader=function(){if($("#tinyloader").length){$("#loaderfade").remove()}};this.RightPopup=function(){if(!$("#rightpopup").length){var a=$('<div id="rightpopup"><div><span id="closerightpopup">x</span></div><div id="rightcontent"></div></div>');a.appendTo("body")}};this.CloseRightPopup=function(){if($("#rightpopup").length){$("#rightpopup").remove()}};this.RightContent=function(a){$("#rightcontent").html("");a.appendTo("#rightcontent")};this.InfoBox=function(){var a=$('<div id="infobox"><a href="http://twitter.com/statuspeople" class="icon" data-tip="Follow Us" id="infotwitter" target="_blank">+</a><a href="http://facebook.com/StatusPeople" class="icon" data-tip="Like Our Facebook Page" id="infofacebook" target="_blank">,</a><a href="mailto:info@statuspeople.com" class="icon" data-tip="Email Us" id="infoemail" target="_blank">%</a><a href="http://blog.statuspeople.com/Posts/RSS" class="icon" data-tip="Subscribe to our Blog" id="infofeed" target="_blank">/</a></div>');a.appendTo("body")};this.BuildRightInfoBox=function(){var a=$('<div id="rightinfobox"><div id="rightinfoclose">x</div><div class="content"></div></div>');a.appendTo("body")};this.RightInfoContent=function(a){a.appendTo("#rightinfobox .content")};this.RightInfoClose=function(){$("#rightinfobox").remove()}}function Scroll(){this.To=function(c,b,a){$("html, body").animate({scrollTop:$(c).offset().top-a},b)}}function Server(){this.CallServer=function(d,b,c,e,a,f){$.ajax({type:d,url:c,dataType:b,data:e,cache:false,statusCode:{404:function(){alert("Page not found!")}},success:function(i){var h=a.split("_");var k=h[0];var g=h[1];var j=new window[k]();j[g](i,f)},complete:function(g,h){console.log(g.status);console.log(g.responseText)},error:function(){pop=new Popup();pop.RemoveTinyLoader();ms=new Messages();ms.Build("failure",["An error occurred: Process Failed!"],".header")}})}}function Spam(){this.ProcessSpamData=function(m,v){if(m.code==201){var r=0;var g=0;var j=0;var c=m.data.checks;if(c>0){r=Math.round((m.data.spam/c)*100);g=Math.round((m.data.potential/c)*100);j=100-(r+g)}var z=$("<div/>");z.attr("class","three a red");z.html('<h1 class="red">Fake</h1><h2 class="red">'+r+"%</h2>");if(v==1){$("#spamscore").val(r)}var t=$("<div/>");t.attr("class","three a");t.html("<h1>Inactive</h1><h2>"+g+"%</h2>");var l=$("<div/>");l.attr("class","three");l.html('<h1 class="green">Good</h1><h2 class="green">'+j+"%</h2>");z.appendTo("#scoresholder");t.appendTo("#scoresholder");l.appendTo("#scoresholder");var e=$("<div/>");e.attr("class","row");e.attr("id","shareform");var b=$("<input/>");b.attr("type","button");if(v==1||v==3){b.attr("id","sharescores");b.val("Share Your Scores on Twitter")}else{if(v==2){b.attr("id","resetscores");b.val("Display Your Scores")}}b.appendTo(e);e.insertAfter("#scoresholder");$.cookie("searches",m.data.searches,{expires:1000});$("#friendsearches").text(m.data.searches);if(parseInt(m.data.searches)==0){var u=new Messages();u.Build("alert",['To run unlimited friend searches please purchase a <a href="/Payments/Subscriptions">subscription</a>.'],".header")}if(!$("#extrainfo").length&&v!=3){var i=0;var x="English";if(m.data.lang!=null){i=m.data.lang.count;var x=m.data.lang.name}var w=0;if(m.data.hundred!=null){w=m.data.hundred}var d=0;if(m.data.fr250!=null){d=m.data.fr250}var a=Math.round((i/m.data.checks)*100);var h=Math.round((w/m.data.checks)*100);var q=Math.round((d/m.data.checks)*100);var p=$('<div id="extrainfo" class="textleft row"></div>');var f=$('<div class="two a"><h2>Your Followers</h2><p class="f2 sp2 blue">'+a+"% speak "+x+'</p><p class="f2 sp2 blue">'+h+'% have not tweeted in 100 days</p><p class="f2 sp2 blue">'+q+"% follow less than 250 people</p></div>");var n="";if(m.data.spam1!=null&&m.data.spam2==null){n=$('<div class="two"><h2>Your Fakers</h2><ul class="fakeslist"><li><img src="'+m.data.spam1.image+'" height="48px" width="48px"/> <span class="red">'+m.data.spam1.screen_name+'</span><small><a href="#details" class="details" data-sc="'+m.data.spam1.screen_name+'">Details</a></small></li></ul></div>')}else{if(m.data.spam1!=null&&m.data.spam2!=null){n=$('<div class="two"><h2>Your Fakers</h2><ul class="fakeslist"><li><img src="'+m.data.spam1.image+'" height="48px" width="48px"/> <span class="red">'+m.data.spam1.screen_name+'</span><small><a href="#details" class="details" data-sc="'+m.data.spam1.screen_name+'">Details</a></small></li><li><img src="'+m.data.spam2.image+'" height="48px" width="48px"/> <span class="red">'+m.data.spam2.screen_name+'</span><small><a href="#details" class="details" data-sc="'+m.data.spam2.screen_name+'">Details</a></small></li></ul></div>')}else{n=$('<div class="two"><h2>Your Fakers</h2><p class="f2 sp2 green">Yay!! You have no Fake Followers</p></div>')}}var y=$('<div class="row"><div class="one center f2 sp2"><a href="/Payments/Details" class="orange">Find out more about your followers &mdash; purchase a subscription</a></div></div>');f.appendTo(p);n.appendTo(p);y.appendTo(p);p.insertAfter("#SearchForm")}}else{var k=$("<h1/>");var o=$("<small/>");if(m.code==429){k.text("We are sorry but you have breached your Twitter API 1.1 Limit.");k.appendTo("#scoresholder");o.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');o.appendTo("#scoresholder")}else{k.text("No data returned for this user.");k.appendTo("#scoresholder");o.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');o.appendTo("#scoresholder")}}$("#loader").hide("slow").remove();$("#searchsubmit").removeAttr("disabled")};this.ProcessSpamDataAdvanced=function(n,f){if(n.code==201){var l=0;var j=0;var i=0;var d=n.data.checks;if(d>0){l=Math.round((n.data.spam/d)*100);j=Math.round((n.data.potential/d)*100);i=100-(l+j)}var k=$("<div/>");k.attr("class","three a red");k.html('<h1 class="red">Fake</h1><h2 class="red">'+l+"%</h2>");var b=$("<div/>");b.attr("class","three a");b.html("<h1>Inactive</h1><h2>"+j+"%</h2>");var c=$("<div/>");c.attr("class","three");c.html('<h1 class="green">Good</h1><h2 class="green">'+i+"%</h2>");k.appendTo("#scoresholder");b.appendTo("#scoresholder");c.appendTo("#scoresholder");var g=$("<div/>");g.attr("class","row");g.attr("id","shareform");var e=$("<input/>");e.attr("type","button");e.attr("id","resetscores");e.val("Display Your Scores");var a=new Server();a.CallServer("GET","JSON","/API/GetCompetitorCount","rf=json&usr="+encodeURIComponent(f),"Spam_AddCompetitorButton");e.appendTo(g);g.insertAfter("#scoresholder");$("#spam").val(n.data.spam);$("#potential").val(n.data.potential);$("#checks").val(n.data.checks);$("#followers").val(n.data.followers)}else{var h=$("<h1/>");var m=$("<small/>");if(n.code==429){h.text("We are sorry but you have breached your Twitter API 1.1 Limit.");h.appendTo("#scoresholder");m.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');m.appendTo("#scoresholder")}else{h.text("No data returned for this user.");h.appendTo("#scoresholder");m.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');m.appendTo("#scoresholder")}}$("#loader").hide("slow").remove();$("#searchsubmit").removeAttr("disabled")};this.ProcessSpamDataPopup=function(o,f){var l=new Popup();if(o.code==201){var m=0;var j=0;var i=0;var e=o.data.checks;if(e>0){m=Math.round((o.data.spam/e)*100);j=Math.round((o.data.potential/e)*100);i=100-(m+j)}var a=$('<h2 id="friendsearchname">'+$("#friendsearchquery").val()+"</h2>");var k=$("<div/>");k.attr("class","three a red");k.html('<h1 class="red">Fake</h1><h2 class="red">'+m+"%</h2>");var c=$("<div/>");c.attr("class","three a");c.html("<h1>Inactive</h1><h2>"+j+"%</h2>");var d=$("<div/>");d.attr("class","three");d.html('<h1 class="green">Good</h1><h2 class="green">'+i+"%</h2>");var g=$("<div/>");a.appendTo(g);k.appendTo(g);c.appendTo(g);d.appendTo(g);l.Content(g);var b=new Server();b.CallServer("GET","JSON","/API/GetCompetitorCount","rf=json&usr="+encodeURIComponent(f),"Spam_AddCompetitorButtonPopup");$("#spam").val(o.data.spam);$("#potential").val(o.data.potential);$("#checks").val(o.data.checks);$("#followers").val(o.data.followers)}else{var h=$("<h1/>");var n=$("<small/>");if(o.code==429){h.text("We are sorry but you have breached your Twitter API 1.1 Limit.");l.Content(h);n.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');l.Content(n)}else{h.text("No data returned for this user.");l.Content(h);n.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');l.Content(n)}}l.RemoveTinyLoader()};this.ProcessCachedSpamData=function(k){if(k.code==201){var i=k.data.Fake[0].count;var f=k.data.Good[0].count;var g=k.data.Inactive[0].count;var h=$("<div/>");h.attr("class","three a red");h.html('<h1 class="red">Fake</h1><h2 class="red">'+i+"%</h2>");$("#spamscore").val(i);var a=$("<div/>");a.attr("class","three a");a.html("<h1>Inactive</h1><h2>"+g+"%</h2>");var b=$("<div/>");b.attr("class","three");b.html('<h1 class="green">Good</h1><h2 class="green">'+f+"%</h2>");h.appendTo("#scoresholder");a.appendTo("#scoresholder");b.appendTo("#scoresholder");var d=$("<div/>");d.attr("class","row");d.attr("id","shareform");var c=$("<input/>");c.attr("type","button");c.attr("id","sharescores");c.val("Share Your Scores on Twitter");c.appendTo(d);d.insertAfter("#scoresholder")}else{var e=$("<h1/>");var j=$("<small/>");if(k.code==429){e.text("We are sorry but you have breached your Twitter API 1.1 Limit.");e.appendTo("#scoresholder");j.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');j.appendTo("#scoresholder")}else{e.text("No data returned for this user.");e.appendTo("#scoresholder");j.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');j.appendTo("#scoresholder")}}$("#loader").hide("slow").remove()};this.ProcessSpamDataFirstTime=function(n,f){if(n.code==201){var l=0;var j=0;var i=0;var d=n.data.checks;if(d>0){l=Math.round((n.data.spam/d)*100);j=Math.round((n.data.potential/d)*100);i=100-(l+j)}var k=$("<div/>");k.attr("class","three a red");k.html('<h1 class="red">Fake</h1><h2 class="red">'+l+"%</h2>");var b=$("<div/>");b.attr("class","three a");b.html("<h1>Inactive</h1><h2>"+j+"%</h2>");var c=$("<div/>");c.attr("class","three");c.html('<h1 class="green">Good</h1><h2 class="green">'+i+"%</h2>");k.appendTo("#scoresholder");b.appendTo("#scoresholder");c.appendTo("#scoresholder");var g=$("<div/>");g.attr("class","row");g.attr("id","shareform");var a=new Server();a.CallServer("GET","json","/API/GetSpamList","rf=json&usr="+encodeURIComponent(f),"Spam_BuildFakersList","");var e=$("<input/>");e.attr("type","button");e.attr("id","sharescores");e.val("Share Your Scores");e.appendTo(g);g.insertAfter("#scoresholder");$("#spam").val(n.data.spam);$("#potential").val(n.data.potential);$("#checks").val(n.data.checks);$("#followers").val(n.data.followers)}else{var h=$("<h1/>");var m=$("<small/>");if(n.code==429){h.text("We are sorry but you have breached your Twitter API 1.1 Limit.");h.appendTo("#scoresholder");m.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');m.appendTo("#scoresholder")}else{h.text("No data returned for this user.");h.appendTo("#scoresholder");m.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');m.appendTo("#scoresholder")}}$("#loader").hide("slow").remove()};this.ProcessSpamScoreShare=function(a){var b=$("<h2/>");if(a.code==201){b.text("Scores shared to Twitter.")}else{b.text("Failed to share scores to Twitter.")}$("#sharing").remove();b.appendTo("#shareform")};this.ProcessCacheData=function(a){if(a.code==201){var e=new Object();e.code=201;e.data=a.data.lang;var d=new Charts();d.BuildChart(e,[{id:"langchart",type:"pie",multi:false,size:"large",xreverse:false,backgroundcolor:"#fefefe",colors:["#FE7D1D","#36B6D5","#2AFE1B","#FE1B2A","#7D1BFE"]}]);this.BuildAverages(a.data.avg)}else{var c=new Messages();c.build("failure",["No language or average data was returned."],".header")}var b=new Popup();b.RemoveTinyLoader()};this.BuildAverages=function(d){if($("#averages").length){$("#averages").remove()}var l=new Format();var i=$('<ul id="averages"/>');var m=$("<li>Tweet "+Math.round(d.tweets_pd/d.count)+" time(s) per day</li>");var e=$("<li>"+Math.round((d.one/d.count)*100)+"% have not tweeted in one day</li>");var c=$("<li>"+Math.round((d.thirty/d.count)*100)+"% have not tweeted in thrity days</li>");var o=$("<li>"+Math.round((d.hundred/d.count)*100)+"% have not tweeted in a hundred days</li>");var j=$("<li>On average have "+l.Number(Math.round(d.followers/d.count))+" followers</li>");var g=$("<li>"+Math.round((d.fo250/d.count)*100)+"% have less than 250 followers</li>");var n=$("<li>"+Math.round((d.fo500/d.count)*100)+"% have about 500 followers</li>");var f=$("<li>"+Math.round((d.fo1000/d.count)*100)+"% have more than 1,000 followers</li>");var h=$("<li>On average follow "+l.Number(Math.round(d.friends/d.count))+" friends</li>");var k=$("<li>"+Math.round((d.fr250/d.count)*100)+"% follow less than 250 friends</li>");var a=$("<li>"+Math.round((d.fr500/d.count)*100)+"% follow about 500 friends</li>");var b=$("<li>"+Math.round((d.fr1000/d.count)*100)+"% follow more than 1,000 friends</li>");m.appendTo(i);e.appendTo(i);c.appendTo(i);o.appendTo(i);j.appendTo(i);g.appendTo(i);n.appendTo(i);f.appendTo(i);h.appendTo(i);k.appendTo(i);a.appendTo(i);b.appendTo(i);i.appendTo("#followerdata")};this.Sharing=function(){$("#sharescores").remove();var a=$("<div/>");a.attr("id","sharing");var b=$("<img/>");b.attr("src","/Pie/Crust/Template/img/287.gif");b.attr("id","imageloader");b.appendTo(a);a.appendTo("#shareform")};this.AddCompetitorButton=function(a){var c=new Messages();var d=new Array();if(a.code==201){var b=$("<input/>");b.attr("type","button");b.attr("id","addfaker");b.attr("value","Add User To Fakers List");if(a.data.competitors>=6&&a.data.type==1){b.attr("disabled","disabled");d[0]="You cannot add any more users to your fakers list.";c.Build("alert",d,".header")}else{if(a.data.competitors>=16&&a.data.type==2){b.attr("disabled","disabled");d[0]="You cannot add any more users to your fakers list.";c.Build("alert",d,".header")}}b.appendTo("#shareform")}else{d[0]="Could not find user details on Twitter please try your search again.";c.Build("failure",d,".header")}};this.AddCompetitorButtonPopup=function(a){var d=new Messages();var e=new Array();var c=new Popup();if(a.code==201){var b=$('<form><fieldset><input type="button" id="addfakerpopup" value="Add User To Friend List"/></fieldset></form>');c.AddContent(b);if(a.data.competitors>=6&&a.data.type==1){$("#addfakerpopup").attr("disabled","disabled");c.AddMessage("You cannot add any more users to your fakers list.",true)}else{if(a.data.competitors>=16&&a.data.type==2){$("#addfakerpopup").attr("disabled","disabled");c.AddMessage("You cannot add any more users to your fakers list.",true)}}}else{c.AddMessage("Could not find user details on Twitter please try your search again.",true)}};this.AddFaker=function(a,d){var c=new Messages();var e=new Array();var b=new Server();if(a.code==201){e[0]='User added to <a href="/Fakers/Followers">Friend List</a>.';c.Build("success",e,".header")}else{e[0]="Failed to add user to Fakers List, please try again";c.Build("failure",e,".header")}b.CallServer("GET","json","/API/GetCompetitorList","rf=json&usr="+encodeURIComponent(d),"Spam_BuildCompetitorList","")};this.DeleteFaker=function(a,d){var c=new Messages();var e=new Array();var b=new Server();if(a.code==201){e[0]="User removed from Fakers List.";c.Build("success",e,".header")}else{c.Build("failure",[a.message],".header")}b.CallServer("GET","json","/API/GetCompetitorList","rf=json&usr="+encodeURIComponent(d),"Spam_BuildCompetitorList","")};this.BuildCompetitorList=function(a){if(a.code==201){var b;var c;var d;if($(".competitorlist").length){$(".competitorlist").remove()}var e=$('<table class="competitorlist"/>');$.each(a.data,function(f,g){b=Math.round((g.spam/g.checks)*100);c=Math.round((g.potential/g.checks)*100);d=(100-(b+c));var h=$("<tr/>");h.html('<td><img src="'+g.avatar+'" width="36px" height="36px" /></td><td><span class="blue">'+g.screen_name+'</span></td><td><span class="red">Fake: '+b+'%</span></td><td><span class="orange">Inactive: '+c+'%</span></td><td><span class="green">Good: '+d+'%</span></td><td><input type="hidden" value="'+g.twitterid+'" class="ti"/><input type="hidden" value="'+g.screen_name+'" class="sc"/><span class="chart icon" data-tip="View on chart"><img src="/Pie/Crust/Template/img/Reports.png" height="24px" width="22px"/></span></td><td><input type="hidden" value="'+g.twitterid+'"/><span class="delete icon" data-tip="Remove">X</span></td>');h.appendTo(e)});e.appendTo("#fakerslist")}};this.BuildFakersList=function(a){var b=new Popup();b.RemoveTinyLoader();if(a.code==201){if($("#checkform").length){$("#checkform").remove()}if($("#spammers .fakeslist").length){$("#spammers .fakeslist").remove()}var d=$('<ul class="fakeslist"/>');$.each(a.data,function(k,l){var j=$("<li/>");j.html('<input type="hidden" value="'+l.screen_name+'" class="sc" /><input type="hidden" value="'+l.twitterid+'" class="ti"/><img src="'+l.avatar+'" width="48px" height="48px" /> '+l.screen_name+'<small><a href="#details" class="details">Details</a> | <a href="#block" class="block">Block</a> | <a href="#spam" class="notspam">Not Spam</a></small>');j.appendTo(d)});d.appendTo("#spammers")}else{if($("#spammers .fakeslist").length){$("#spammers .fakeslist").remove()}if(!$("#checkform").length){var h=$('<div id="checkform"/>');var g=$("<p/>");g.text("No Fake Followers found at this time.");g.appendTo(h);var e=$('<p><fieldset><input type="button" id="checkfakes" value="Check For New Fakes"/></fieldset></p>');e.appendTo(h);h.appendTo("#spammers")}var c=new Messages();c.Build("alert",["No Fake Followers found at this time, please try again later."],".header")}};this.BuildBlockedList=function(a){var b=new Popup();b.RemoveTinyLoader();if(a.code==201){if($("#blocked .fakeslist").length){$("#blocked .fakeslist").remove()}var c=$('<ul class="fakeslist"/>');$.each(a.data,function(e,g){var d=$("<li/>");d.html('<input type="hidden" value="'+g.screen_name+'" class="sc" /><input type="hidden" value="'+g.twitterid+'" class="ti"/><img src="'+g.avatar+'" width="48px" height="48px" /> '+g.screen_name+'<small><a href="#details" class="details">Details</a> | <a href="#block" class="unblock">Unblock</a>');d.appendTo(c)});c.appendTo("#blocked")}};this.UpdateFakersList=function(a,c){var b=new Server();if(a.code==201){b.CallServer("GET","json","/API/GetSpamList","rf=json&usr="+encodeURIComponent(c),"Spam_BuildFakersList","")}else{if(a.code==429){var d=new Messages();d.Build("alert",a.message,".header")}b.CallServer("GET","json","/API/GetSpamList","rf=json&usr="+encodeURIComponent(c),"Spam_BuildFakersList","")}};this.BuildUser=function(a){var c=new Tweets();var b=new Popup();b.RemoveMessage();b.RemoveLoader();if(a.code==201){var e=c.BuildUser(a.data);var d=$("<ul/>");d.addClass("twitteruser");e.appendTo(d);b.Content(d)}else{b.AddMessage("No user data returned",true)}};this.BlockUser=function(a,d){var c=new Messages();var e=new Array();var b=new Server();if(a.code==201){e[0]="User blocked successfully.";c.Build("success",e,".header")}else{e[0]="Failed to block user, please try again.";c.Build("failure",e,".header")}b.CallServer("GET","json","/API/GetSpamList","rf=json&usr="+encodeURIComponent(d),"Spam_BuildFakersList","");b.CallServer("GET","json","/API/GetBlockedList","rf=json&usr="+encodeURIComponent(d),"Spam_BuildBlockedList","")};this.UnBlockUser=function(a,d){var c=new Messages();var e=new Array();var b=new Server();if(a.code==201){e[0]="User unblocked successfully.";c.Build("success",e,".header")}else{e[0]="Failed to unblock user, please try again.";c.Build("failure",e,".header")}b.CallServer("GET","json","/API/GetSpamList","rf=json&usr="+encodeURIComponent(d),"Spam_BuildFakersList","");b.CallServer("GET","json","/API/GetBlockedList","rf=json&usr="+encodeURIComponent(d),"Spam_BuildBlockedList","")};this.NotSpam=function(a,d){var c=new Messages();var e=new Array();var b=new Server();if(a.code==201){e[0]="User successfully marked as not spam.";c.Build("success",e,".header")}else{e[0]="Failed to mark user as not spam, please try again.";c.Build("failure",e,".header")}b.CallServer("GET","json","/API/GetSpamList","rf=json&usr="+encodeURIComponent(d),"Spam_BuildFakersList","")};this.ProcessUserCheck=function(a){var b=new Popup();if(a.code==500){b.BuildRightInfoBox();var c=$('<p><strong>Get 5 Free Friend Searches.</strong></p><p>Just give us a few details.</p><input type="button" id="freesearches" value="Free Searches" /><p><strong>Get Unlimited Friend Searches</strong></p><p>Sign up for a subscription.</p><a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a><p><strong>Auto Block</strong></p><p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p><form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');b.RightInfoContent(c)}else{if(a.code==201){b.BuildRightInfoBox();var c=$('<p><strong>Get Unlimited Friend Searches</strong></p><p>Sign up for a subscription.</p><a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a><p><strong>Auto Block</strong></p><p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p><form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');b.RightInfoContent(c)}}};this.ProcessUserAddDetails=function(a){var b=new Popup();var c=new Messages();if(a.code==201){c.Build("success",['User details added successfully. And you now have 5 extra friend searches. If you want unlimited searches <a href="/Payments/Subscriptions">sign up for a subscription</a>.'],".header");b.RemovePopup();$.cookie("searches",a.data.searches,1000);$("#friendsearches").text(a.data.searches);b.RightInfoClose();b.BuildRightInfoBox();var d=$('<p><strong>Get Unlimited Friend Searches</strong></p><p>Sign up for a subscription.</p><a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a><p><strong>Auto Block</strong></p><p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p><form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');b.RightInfoContent(d)}else{if(a.code==500){c.Build("failure",["Failed to create user."],".header");b.RemovePopup()}else{b.AddMessage(a.message,true)}}b.RemoveTinyLoader()};this.ProcessFakerFind=function(b){if($("#blocksearchdata .fakeslist").length){$("#blocksearchdata .fakeslist").remove()}var c=$('<ul class="fakeslist"/>');if(b.code=201){$.each(b.data,function(e,g){var d=$("<li/>");d.html('<input type="hidden" value="'+g.screen_name+'" class="sc" /><input type="hidden" value="'+g.twitterid+'" class="ti"/><img src="'+g.avatar+'" width="48px" height="48px" /> '+g.screen_name+'<small><a href="#details" class="details">Details</a> | <a href="#block" class="unblock">Unblock</a>');d.appendTo(c)})}else{var a=$("<li>No Blocked Followers found.</li>");a.appendTo(c)}c.appendTo("#blocksearchdata")};this.AutoBlockUpdate=function(a){var d=new Messages();var b=new Popup();if(a.code==201){var e;if(a.data==1){d.Build("success",["Auto blocking has been turned on."],".header");e=$(' <span class="green">On <span class="ico">;</span></span> <span id="autooff" class="microbutton pointer">Turn Off</span>')}else{d.Build("success",["Auto blocking has been turned off."],".header");e=$('<span class="red">Off <span class="ico" style="font-size:20px;">9</span></span> <span id="autoon" class="microbutton pointer">Turn On</span>')}var c=$("<h2>Auto Block Fakes </h2>");e.appendTo(c);$("#autoblock .two h2").remove();c.appendTo("#autoblock .two")}else{d.Build("failure",["Failed to change your auto blocking status."],".header")}b.RemovePopup();b.RemoveTinyLoader()}}function Tweets(){this.BuildTweet=function(f){var c=$("<li/>");var b=$("<img/>");b.attr("src",f.avatar);b.attr("height","48px");b.attr("width","48px");b.appendTo(c);var e=$("<p/>");var a=this.HashAtLink(f.tweet);e.append("<strong>"+f.name+"</strong> ");e.append(a);e.appendTo(c);var d=$("<small/>");d.addClass("orange");d.html("Source: "+f.source+" | Date: "+f.date);d.appendTo(c);return c};this.HashAtLink=function(g){var f=new RegExp("https?:\\/\\/([a-zA-Z0-9\\/\\_\\-\\.\\(\\)\\&\\?\\=]*)","g");var e=new RegExp("^@([a-zA-Z0-9_-]{1,})","g");var d=new RegExp("\\s@([a-zA-Z0-9_-]{1,})","g");var c=new RegExp("^#([a-zA-Z0-9_-]{1,})","g");var b=new RegExp("\\s#([a-zA-Z0-9_-]{1,})","g");var a=g.replace(f,'<a href="http://$1" target="_blank" class="createdlink">$1</a>');a=a.replace(e,'<a href="http://twitter.com/$1" class="tweetuser" target="_blank">@$1</a>');a=a.replace(d,' <a href="http://twitter.com/$1" class="tweetuser" target="_blank">@$1</a>');a=a.replace(c,' <a href="http://twitter.com/search?q=%23$1" class="tweethash" target="_blank">#$1</a>');a=a.replace(b,' <a href="http://twitter.com/search?q=%23$1" class="tweethash" target="_blank">#$1</a>');return a};this.GetAtUsers=function(a){var c=new RegExp("\\s@([a-zA-Z0-9_-]{1,})","g");var b=a.match(c);return b};this.CreateRetweet=function(a){var e=new RegExp("^([a-zA-Z0-9_-]{1,})\\s");var d=new RegExp("([a-zA-Z0-9]{1,}\\.[a-zA-Z0-9]{1,}\\/[a-zA-Z0-9]{1,})","g");var c=a.match(e);var b="RT @"+c[1]+": ";var f=a.replace(e,b);f=f.replace(d,"http://$1");return f};this.BuildUser=function(m,i){var l=$("<li/>");var a=$('<div class="userholder"/>');var j=$("<div/>");var f=$('<img width="48" height="48" src="'+m.image+'"/>');f.appendTo(j);var e="green";if(m.spam=="Inactive"){e="orange"}else{if(m.spam=="Fake"){e="red"}}var k=$('<div class="fakers center orange"><strong>Fakers</strong><br/><strong><span class="'+e+'">'+m.spam+"</span></strong></div>");k.appendTo(j);var c=$('<div class="kredsmall"><div class="influence"></div><div class="outreach"></div></div>');c.appendTo(j);j.appendTo(a);var h=$("<div/>");h.html('<span class="screenname">'+m.screenname+"</span><br/>"+m.location+'<br/><a href="'+m.url+'" target="_blank">Website</a><br/>Tweets: '+m.tweets+"<br/>Followers: "+m.followers+' <a href="http://twitter.com/'+m.screenname+'" class="tweetfollowers">View</a><br/>Friends: '+m.friends+"<br/>Days Active: "+m.daysactive);var b=new Server();b.CallServer("GET","json","/API/GetKredScore","rf=json&gk="+i+"&usr="+m.screenname,"Tweets_AddKredScore",c);h.appendTo(a);var g=$("<div/>");g.text(m.description);g.appendTo(a);var d=$("<div/>");d.html('<a href="http://twitter.com/'+m.screenname+'" class="usertweettimeline" title="User Timeline">"</a>');d.appendTo(a);a.appendTo(l);return l};this.BuildDM=function(a){};this.BuildRetweet=function(a){};this.AddKredScore=function(a,b){if(a.code==201){b.children(".influence").html(a.data.influence);b.children(".outreach").html(a.data.outreach)}else{b.children(".influence").html("0");b.children(".outreach").html("0")}};this.Timeline=function(j,a){var e=new Tweets();var g=new Hub();var b="#twitter"+a;var d="#timelineholder"+a;if(j.code==201){var h=0;if($(b).length){$.each(j.data,function(c,l){if(!h){$("#sinceid"+a).val(l.id)}var k=e.BuildTweet(l);k.prependTo(b);h=h+1})}else{var f=$("<ul/>");f.attr("id","twitter"+a);f.addClass("twittertimeline");$.each(j.data,function(c,l){if(!h){$("#sinceid"+a).val(l.id)}var k=e.BuildTweet(l);k.appendTo(f);h=h+1});f.appendTo(d)}}else{if($(b).length){}else{var i=new Popup();i.Loader();i.AddMessage("No data returned",true)}}if($("#viewnewtweets").length){$("#viewnewtweets").remove()}g.Loader("")};this.Followers=function(a){var c=new Popup();var b=new Tweets();if(a.code==201){var d=$("<ul/>");d.addClass("twitterusers");$.each(a.data,function(f,e){var g=b.BuildUser(e);g.appendTo(d)});c.Content(d,"popupscroll")}else{c.AddMessage("No follower data returned at this time.",true)}c.RemoveLoader()};this.NewCount=function(a){if(a.code==201){var b=new Hub();var e=0;$.each(a.data,function(c,f){e+=1});if($("#viewnewtweets").length){$("#viewnewtweets").text("You have "+e+" new Tweets to view.")}else{var d=$("<div/>");d.attr("id","viewnewtweets");d.text("You have "+e+" new Tweets to view.");d.insertBefore("#timelineholderhome")}}b.Loader("")};this.BuildUserTimeline=function(a){var c=new Tweets();var b=new Popup();b.RemoveLoader();b.RemoveMessage();if(a.code==201){var d=$('<ul class="twittertimeline"/>');$.each(a.data,function(f,e){var g=c.BuildTweet(e);g.prependTo(d)});b.Content(d,"popupscroll")}else{b.AddMessage("No tweets found for this user",true)}}}function Format(){this.Number=function(a){return a.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")}};