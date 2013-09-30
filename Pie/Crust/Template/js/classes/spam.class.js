function Charts()
{
    
    this.BuildChart = function(result)
    {
        
        if (result.code == 201)
        {
            options = {
                chart: {
                    renderTo: 'chart',
                    defaultSeriesType: 'line'
                },
                title: {
                    text: '',
                    x: 0, //center
                    align:'right',
                    style: {color: '#fe7d1d',fontSize:'12px'}
                },
                xAxis: {
                    categories: [],
                    labels: {
                        enabled: true
                    },
                    reversed:true
                },
                yAxis: {
                    title: {
                        text: '',
                        style: {color: '#36b6d5'}
                    },
                    plotLines: [{
                        value: 0,
                        width: 1
                    }],
                    min:0,
                    showFirstLabel:true
                },
                tooltip: {
                    formatter: function () {
                        return this.series.name + ': ' + this.y +'%';
                    }
                },
                legend: {
                    enabled: false
                },
                colors: ['#FE1B2A', '#fe7d1d', '#2AFE1B'],
                series: []
            };

            $.each(result.data, function (i, r) {

                var interactions = {
                    data: []
                };

                interactions.name = i;

                $.each(r, function (i, grdata) {

                    options.xAxis.categories.push(grdata.date);
                    interactions.data.push(parseFloat(grdata.count));

                });

                options.series.push(interactions);

            });

            var chart = new Highcharts.Chart(options);
        }
        
    }
    
}

function Lengths()
{
    this.GetLength = function(message,limit,urls)
    {
        var linklength = 0;
        var newlength = 0;

        if (urls)
        {
            var rg1 = new RegExp('http:\\/\\/([a-zA-Z0-9\\/\\_\\-\\.\\(\\)\\&\\?\\=\\%\\#\\!]*)','g');

            var matches = message.match(rg1);

            if (matches)
            {
                $.each(matches, function(i,v){

                    linklength += v.length;
                    newlength += 25;

                });
            }
        }
        
        var ml = message.length;

        ml = ml - linklength;
        ml = ml + newlength;

        ml = limit - ml;
        
        return ml;
    }
    
    this.StringLength = function(string)
    {
        return string.length;
    }
}

function Messages()
{
    
    this.Build = function (type,messages,where)
    {
        if ($('#'+type+'message').length)
        {
            $('#'+type+'message').remove();
        }
        
        var ul = $('<ul/>');

        $.each(messages,function(i,m){
            var li = $('<li/>');
            li.html(m);
            li.appendTo(ul);
        });

        var div1 = $('<div class="'+type+'message bree" id="'+type+'message"/>');
        var div2 = $('<div class="e1"/>');
        div2.html('<img src="/Pie/Crust/Template/img/'+type+'_icon.png" />');
        div2.appendTo(div1);
        var div3 = $('<div class="e2 s0"/>');
        ul.appendTo(div3);
        div3.appendTo(div1);
        var div4 = $('<div class="e3 s0 '+type+'close"/>');
        div4.html('<span id="'+type+'close">X</span>');
        div4.appendTo(div1);

        div1.insertAfter(where);
        
		function Remove()
		{
			var ms = new Messages();
			ms.CloseMessages(['#'+type+'message']);
		}
		
        var st2 = setTimeout(Remove,15000);
    }
    
    this.DeleteCheck = function(id)
    {
        var pop = new Popup();
                
        pop.Loader();
        pop.AddMessage('Are You Sure?');

        var div = $('<div/>');

        var input = $('<input id="deletedata" type="hidden"/>');
        input.val(id);
       
        input.appendTo(div);

        var ul = $('<ul class="nav"/>');

        var li1 = $('<li id="deleteyes" class="microbutton pointer"/>');
        li1.text('Yes');
        li1.appendTo(ul);

        var li2 = $('<li id="deleteno" class="microbutton pointer"/>');
        li2.text('No');
        li2.appendTo(ul);

        ul.appendTo(div);

        pop.Content(div);
    }
    
	this.CloseMessages = function(messages)
	{
		$.each(messages,function(e,m){
		
			if ($(m).length)
			{
				$(m).fadeOut("slow",function(){$(this).remove();});
			}
			
		});
	}
	
}

function Payments()
{
    
    this.RoundNumber = function(num, decimals) 
    { 
        var newnumber = Math.round(num*Math.pow(10,decimals))/Math.pow(10,decimals);
        
        return parseFloat(newnumber); 
    }
    
    this.RecalculateCart = function(currency,months)
    {
        
        var cur = '&pound;';
        var base = 3.49;
        var tax = 0.70;
        
        if (currency == 'USD')
        {
            cur = '&#36;';
            base = 5.49;
            tax = 0.00;
        }
        else if (currency == 'EUR')
        {
            cur = '&euro;';
            base = 4.49;
            tax = 0.90;
        }
        
        var tms = 1;
        var svt = 0;
        
        if (months == 6)
        {
            tms = 5;
            svt = 1;
        }
        else if (months == 12)
        {
            tms = 10;
            svt = 2;
        }
        
        var pay = new Payments();
        
        var st = pay.RoundNumber((tms * base),2);
        var sv = pay.RoundNumber((svt * base),2);
        var tx = pay.RoundNumber((tms * tax),2);
        var tot = pay.RoundNumber((st+tx),2);
        
        $('.currency').html(cur);
        $('#cartmonths').text(months);
        $('#cartsubtotal').text(st.toFixed(2));
        $('#cartsaving').text(sv.toFixed(2));
        $('#carttax').text(tx.toFixed(2));
        $('#carttotal').text(tot.toFixed(2));    
        
        $('#subtotal').val(st.toFixed(2));
        $('#tax').val(tx.toFixed(2));
        $('#months').val(months);
        $('#saving').val(sv.toFixed(2));
        
    }
    
}

function Popup()
{
    
    this.BuildPopup = function(data)
    {
        var div1 = $('<div id="loaderfade2"/>');
        
        var box = $("<div/>");
        box.attr('id','popup');

        var close = $("<div/>");
        close.attr('id','popupcloseholder');

        var closelink = $("<a/>");
        closelink.attr('id','popupclose');
        closelink.text("Close");
        closelink.appendTo(close);

        close.appendTo(box);

        var databox = $("<div/>");
        databox.attr('id','popupdata');
        databox.append(data);

        databox.appendTo(box);
        
        box.appendTo(div1);

        div1.appendTo("body");
    }

    this.Loader = function(message)
    {
        var loader = $('<div/>');
        loader.attr('id','popuploader');
        loader.text(message);

        if ($("#popup").length > 0)
        {
            loader.prependTo("#popupdata");
        }    
        else
        {
            this.BuildPopup(loader);
        }
    }

    this.Content = function(data,style)
    {

        if ($('#popupcontent').length > 0)
        {
            $('#popupcontent').remove();    
        }

        var content = $('<div/>');
        content.attr('id','popupcontent');
        content.append(data);

        if (style)
        {
            content.addClass(style);
        }

        content.appendTo("#popupdata");
    }

    this.AddMessage = function(message,error)
    {
        var popupmessage = $('<div/>');

        if (error)
        {
            popupmessage.addClass('persistanterror');
        }
        else
        {
            popupmessage.addClass('persistantsuccess');
        }

        popupmessage.attr('id','popupmessage');

        popupmessage.text(message);

        popupmessage.prependTo("#popupdata");
    }

    this.RemovePopup = function()
    {
        $('#loaderfade2').remove();
//        $("#popup").remove();
    }

    this.RemoveContent = function()
    {
        $("#popupcontent").remove();
    }

    this.RemoveLoader = function()
    {
        $("#popuploader").remove();
    }

    this.RemoveMessage = function()
    {
        $("#popupmessage").remove();
    }
    
    this.TinyLoader = function()
    {
        if (!$('#tinyloader').length)
        {
            var div1 = $('<div id="loaderfade"/>');
            var div2 = $('<div id="tinyloader"><h2>Loading...</h2><img src="http://fakers.statuspeople.com/Pie/Crust/Template/img/287.gif" /></div>');

            div2.appendTo(div1);

            div1.appendTo('body');
        }
    }
    
    this.RemoveTinyLoader = function()
    {
        if ($('#tinyloader').length)
        {
        
            $('#loaderfade').remove();
        
        }
    }
    
	this.RightPopup = function()
	{
		if (!$('#rightpopup').length)
		{
			var div = $('<div id="rightpopup">'
						+'<div><span id="closerightpopup">x</span></div>'
						+'<div id="rightcontent"></div>'
						+'</div>');
			
			div.appendTo('body');
		}
	}
	
	this.CloseRightPopup = function()
	{
		if ($('#rightpopup').length)
		{
			$('#rightpopup').remove();
		}
	}
	
	this.RightContent = function(content)
	{
		$('#rightcontent').html('');
		content.appendTo('#rightcontent');
	}
	
	this.InfoBox = function()
	{
		var div = $('<div id="infobox"><a href="http://twitter.com/statuspeople" class="icon" data-tip="Follow Us" id="infotwitter" target="_blank">+</a>'+
					'<a href="http://facebook.com/StatusPeople" class="icon" data-tip="Like Our Facebook Page" id="infofacebook" target="_blank">,</a>'+
					'<a href="mailto:info@statuspeople.com" class="icon" data-tip="Email Us" id="infoemail" target="_blank">%</a>'+
					'<a href="http://blog.statuspeople.com/Posts/RSS" class="icon" data-tip="Subscribe to our Blog" id="infofeed" target="_blank">/</a></div>');
		
		div.appendTo('body');
	}
	
	this.BuildRightInfoBox = function()
	{
		var div = $('<div id="rightinfobox"><div id="rightinfoclose">x</div><div class="content"></div></div>');	
		div.appendTo('body');
	}
	
	this.RightInfoContent = function(content)
	{
		content.appendTo('#rightinfobox .content');
	}
	
	this.RightInfoClose = function()
	{
		$('#rightinfobox').remove();
	}
}

function Scroll()
{
    
    this.To = function(id,time,minus)
    {
        $('html, body').animate({
            scrollTop: $(id).offset().top-minus
        }, time);
    }
    
}

function Server()
{
    
    this.CallServer = function(type,response,url,data,route,vars)
    {
        $.ajax({
            type: type,
            url: url,
            dataType: response,
            data: data,
            cache: false,
            statusCode: {
                404: function () {
                    alert('Page not found!');
                }
            },
            success: function (result) {

                var cm = route.split('_');
                var c = cm[0];
                var m = cm[1];
                
                var myclass = new window[c]();
                
                myclass[m](result,vars);             
                    
            },
            complete: function (xhr, textStatus) {
                console.log(xhr.status);
                console.log(xhr.responseText);
            }
        });
    }

}

function Spam()
{
    
    this.ProcessSpamData = function(result,source)
    {
        if (result.code == 201)
        {
            var spamscore = 0;
            var potentialscore = 0;
            var goodscore = 0;
            
            var checks = result.data.checks;
            
            if (checks>0)
            {
                spamscore = Math.round((result.data.spam/checks)*100);
                potentialscore = Math.round((result.data.potential/checks)*100);
                goodscore = 100-(spamscore+potentialscore);
            }
            
            var spam = $('<div/>');
            spam.attr('class','three a red');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            if (source==1)
            {
                $('#spamscore').val(spamscore);
            }
            
            var inactive = $('<div/>');
            inactive.attr('class','three a');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
            spam.appendTo('#scoresholder');
            inactive.appendTo('#scoresholder');
            good.appendTo('#scoresholder');
            
            var div2 = $('<div/>');
            div2.attr('class','row');
            div2.attr('id','shareform');
            
            var button = $('<input/>');
            button.attr('type','button');
            
            if (source==1)
            {
                button.attr('id','sharescores');
                button.val('Share Your Scores on Twitter');
            }
            else if (source==2)
            {
                button.attr('id','resetscores');
                button.val('Display Your Scores');    
            }
            
            button.appendTo(div2);
            
            div2.insertAfter('#scoresholder');
			
			$.cookie('searches',result.data.searches,{expires:1000});
			$('#friendsearches').text(result.data.searches);
            
			if (parseInt(result.data.searches)==0)
			{
				var ms = new Messages();
				ms.Build('alert',['To run unlimited friend searches please purchase a <a href="/Payments/Subscriptions">subscription</a>.'],'.header');
			}
        }
        else
        {
            
            var h1 = $('<h1/>');
            var s = $('<small/>');
            
            if (result.code == 429)
            {
                h1.text('We are sorry but you have breached your Twitter API 1.1 Limit.');
                h1.appendTo('#scoresholder');
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                s.appendTo('#scoresholder');
            }
            else
            {
                h1.text('No data returned for this user.');
                h1.appendTo('#scoresholder');
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                s.appendTo('#scoresholder');
            }
            
        }
        
        $('#loader').hide('slow').remove();
        
        $('#searchsubmit').removeAttr('disabled');
        
    }
    
    this.ProcessSpamDataAdvanced = function(result,usr)
    {
        if (result.code == 201)
        {
            var spamscore = 0;
            var potentialscore = 0;
            var goodscore = 0;
            
            var checks = result.data.checks;
            
            if (checks>0)
            {
                spamscore = Math.round((result.data.spam/checks)*100);
                potentialscore = Math.round((result.data.potential/checks)*100);
                goodscore = 100-(spamscore+potentialscore);
            }
            
            var spam = $('<div/>');
            spam.attr('class','three a red');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            var inactive = $('<div/>');
            inactive.attr('class','three a');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
            spam.appendTo('#scoresholder');
            inactive.appendTo('#scoresholder');
            good.appendTo('#scoresholder');
            
            var div2 = $('<div/>');
            div2.attr('class','row');
            div2.attr('id','shareform');
            
            var button = $('<input/>');
            button.attr('type','button');
            
            button.attr('id','resetscores');
            button.val('Display Your Scores');    
                
            var srv = new Server();
            srv.CallServer('GET','JSON','/API/GetCompetitorCount','rf=json&usr='+usr,'Spam_AddCompetitorButton');
            
            button.appendTo(div2);
            
            div2.insertAfter('#scoresholder');
                        
            $('#spam').val(result.data.spam);
            $('#potential').val(result.data.potential);
            $('#checks').val(result.data.checks);
            $('#followers').val(result.data.followers);
        }
        else
        {
            var h1 = $('<h1/>');
            var s = $('<small/>');
            
            if (result.code == 429)
            {
                h1.text('We are sorry but you have breached your Twitter API 1.1 Limit.');
                h1.appendTo('#scoresholder');
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                s.appendTo('#scoresholder');
            }
            else
            {
                h1.text('No data returned for this user.');
                h1.appendTo('#scoresholder');
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                s.appendTo('#scoresholder');
            }
        }
        
        $('#loader').hide('slow').remove();
        
        $('#searchsubmit').removeAttr('disabled');
        
    }
    
    this.ProcessCachedSpamData = function(result)
    {
        if (result.code == 201)
        {
            var spamscore = result.data.Fake[0].count;
            var goodscore = result.data.Good[0].count;
            var potentialscore = result.data.Inactive[0].count;
            
            var spam = $('<div/>');
            spam.attr('class','three a red');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            $('#spamscore').val(spamscore);
            
            var inactive = $('<div/>');
            inactive.attr('class','three a');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
            spam.appendTo('#scoresholder');
            inactive.appendTo('#scoresholder');
            good.appendTo('#scoresholder');
            
            var div2 = $('<div/>');
            div2.attr('class','row');
            div2.attr('id','shareform');
            
            var button = $('<input/>');
            button.attr('type','button');
            
            button.attr('id','sharescores');
            button.val('Share Your Scores on Twitter');
            
            button.appendTo(div2);
            
            div2.insertAfter('#scoresholder');
        }
        else
        {
            var h1 = $('<h1/>');
            var s = $('<small/>');
            
            if (result.code == 429)
            {
                h1.text('We are sorry but you have breached your Twitter API 1.1 Limit.');
                h1.appendTo('#scoresholder');
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                s.appendTo('#scoresholder');
            }
            else
            {
                h1.text('No data returned for this user.');
                h1.appendTo('#scoresholder');
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                s.appendTo('#scoresholder');
            }
        }
        
        $('#loader').hide('slow').remove();
    }
    
    this.ProcessSpamDataFirstTime = function(result,usr)
    {
        if (result.code == 201)
        {
            var spamscore = 0;
            var potentialscore = 0;
            var goodscore = 0;
            
            var checks = result.data.checks;
            
            if (checks>0)
            {
                spamscore = Math.round((result.data.spam/checks)*100);
                potentialscore = Math.round((result.data.potential/checks)*100);
                goodscore = 100-(spamscore+potentialscore);
            }
            
            var spam = $('<div/>');
            spam.attr('class','three a red');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            var inactive = $('<div/>');
            inactive.attr('class','three a');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
            spam.appendTo('#scoresholder');
            inactive.appendTo('#scoresholder');
            good.appendTo('#scoresholder');
            
            var div2 = $('<div/>');
            div2.attr('class','row');
            div2.attr('id','shareform');
            
            var srv = new Server();
            
            srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+usr,'Spam_BuildFakersList','')
            
            var button = $('<input/>');
            button.attr('type','button');
            
            button.attr('id','sharescores');
            button.val('Share Your Scores');
            
            button.appendTo(div2);
            
            div2.insertAfter('#scoresholder');
                        
            $('#spam').val(result.data.spam);
            $('#potential').val(result.data.potential);
            $('#checks').val(result.data.checks);
            $('#followers').val(result.data.followers);
        }
        else
        {
            var h1 = $('<h1/>');
            var s = $('<small/>');
            
            if (result.code == 429)
            {
                h1.text('We are sorry but you have breached your Twitter API 1.1 Limit.');
                h1.appendTo('#scoresholder');
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                s.appendTo('#scoresholder');
            }
            else
            {
                h1.text('No data returned for this user.');
                h1.appendTo('#scoresholder');
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                s.appendTo('#scoresholder');
            }
        }
        
        $('#loader').hide('slow').remove();
        
    }
    
    this.ProcessSpamScoreShare = function(result)
    {
        var h2 = $('<h2/>');
        
        if (result.code==201)
        {
            h2.text('Scores shared to Twitter.');
        }
        else
        {
            h2.text('Failed to share scores to Twitter.');
        }
        
        $('#sharing').remove();
        h2.appendTo('#shareform');
    }
    
    this.Sharing = function()
    {
        $('#sharescores').remove();
        
        var loader = $('<div/>');
        loader.attr('id','sharing');
        
        var img = $('<img/>');
        img.attr('src','/Pie/Crust/Template/img/287.gif');
        img.attr('id','imageloader');
        
        img.appendTo(loader);
        
        loader.appendTo('#shareform');   
    }
    
    this.AddCompetitorButton = function(result)
    {
        var mes = new Messages();
        var ma = new Array();
        
        if (result.code == 201)
        {
            var input = $('<input/>');
            input.attr('type','button');
            input.attr('id','addfaker');
            input.attr('value','Add User To Fakers List');
            
            if (result.data >= 6)
            {
                input.attr('disabled','disabled');
                ma[0]='You cannot add any more users to your fakers list.';
                mes.Build('alert',ma,'.header');
            }
            
            input.appendTo('#shareform');
        }
        else
        {
            ma[0] = 'Could not find user details on Twitter please try your search again.';
            mes.Build('failure',ma,'.header');
        }
        
    }
    
    this.AddFaker = function(result,user)
    {
        var mes = new Messages();
        var ma = new Array();
        var srv = new Server();
        
        if (result.code == 201)
        {
            ma[0]='User added to Fakers List.';
            mes.Build('success',ma,'.header');
        }
        else
        {
            ma[0]='Failed to add user to Fakers List, please try again';
            mes.Build('failure',ma,'.header');
        }
        
        srv.CallServer('GET','json','/API/GetCompetitorList','rf=json&usr='+user,'Spam_BuildCompetitorList','');
        
    }
    
    this.DeleteFaker = function(result,user)
    {
        var mes = new Messages();
        var ma = new Array();
        var srv = new Server();
        
        if (result.code == 201)
        {
            ma[0]='User removed from Fakers List.';
            mes.Build('success',ma,'.header');
        }
        else
        {
            ma[0]='Failed to remove user from Fakers List, please try again';
            mes.Build('failure',ma,'.header');
        }
        
        srv.CallServer('GET','json','/API/GetCompetitorList','rf=json&usr='+user,'Spam_BuildCompetitorList','');
        
    }
    
    this.BuildCompetitorList = function(result)
    {
        
        if (result.code==201)
        {
            var fake;
            var inactive;
            var good;
            
            if ($('.competitorlist').length)
            {
                $('.competitorlist').remove();
            }
            
            var tbl = $('<table class="competitorlist"/>');
            
            $.each(result.data,function(i,r){
                
                fake = Math.round((r.spam/r.checks)*100);
                inactive = Math.round((r.potential/r.checks)*100);
                good = (100-(fake+inactive));
                
                var tr = $('<tr/>');
                tr.html('<td><img src="'+r.avatar+'" width="36px" height="36px" /></td><td><span class="blue">'+r.screen_name+'</span></td><td><span class="red">Fake: '+fake+'%</span></td><td><span class="orange">Inactive: '+inactive+'%</span></td><td><span class="green">Good: '+good+'%</span></td><td><input type="hidden" value="'+r.twitterid+'" class="ti"/><input type="hidden" value="'+r.screen_name+'" class="sc"/><span class="chart" title="View on chart"><img src="/Pie/Crust/Template/img/Reports.png" height="24px" width="22px"/></span></td><td><input type="hidden" value="'+r.twitterid+'"/><span class="delete" title="Remove">X</span></td>');
                tr.appendTo(tbl);
            });
            
            tbl.appendTo('#fakerslist');
        }
        
    }
    
    this.BuildFakersList = function(result)
    {
        
        if ($('#loader').length)
        {
            $('#loader').remove();
        }
        
        if (result.code == 201)
        {
            if ($('.fakeslist').length)
            {
                $('.fakeslist').remove();
            }
            
            var ul = $('<ul class="fakeslist"/>');
            
            $.each(result.data,function(i,f){
                
                var li = $('<li/>');
                li.html('<input type="hidden" value="'+f.screen_name+'" class="sc" /><input type="hidden" value="'+f.twitterid+'" class="ti"/><img src="'+f.avatar+'" width="48px" height="48px" /> '+f.screen_name+'<small><a href="#details" class="details">Details</a> | <a href="#block" class="block">Block</a> | <a href="#spam" class="notspam">Not Spam</a></small>');
                li.appendTo(ul);
                
            });
            
            ul.appendTo('#spammers');
        }
        else
        {
            if ($('.fakeslist').length)
            {
                $('.fakeslist').remove();
            }
            
            var div = $('<div id="checkform"/>');
            
            var p = $('<p/>');
            p.text('No Fake Followers found at this time.');
            
            p.appendTo(div);
            
            var f = $('<p><fieldset><input type="button" id="checkfakes" value="Check For New Fakes"/></fieldset></p>');
            
            f.appendTo(div);
            
            div.appendTo('#spammers');
        }
    }
    
    this.UpdateFakersList = function(result,user)
    {
        
        var srv = new Server();
        
        if (result.code == 201)
        {
            srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+user,'Spam_BuildFakersList','');
        }
        else
        {
            if (result.code==429)
            {
                pop.Loader();
                var pop = new Popup();
                pop.AddMessage(result.message,true);
            }
            
            srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+user,'Spam_BuildFakersList','');
            
        }
        
    }
    
    this.BuildUser = function(result)
    {
        var tw = new Tweets();
        var pop = new Popup();
        
        pop.RemoveMessage();
        pop.RemoveLoader();
        
        if (result.code == 201)
        {
            var list = tw.BuildUser(result.data);
           
            var ul = $('<ul/>');
            ul.addClass('twitteruser');
            
            list.appendTo(ul);
           
            pop.Content(ul);
        }
        else
        {
            pop.AddMessage('No user data returned',true);
        }
    }
    
    this.BlockUser = function(result,user)
    {
        var mes = new Messages();
        var ma = new Array();
        var srv = new Server();
        
        if (result.code == 201)
        {
            ma[0]='User blocked successfully.';
            mes.Build('success',ma,'.header');
        }
        else
        {
            ma[0]='Failed to block user, please try again.';
            mes.Build('failure',ma,'.header');
        }
        
        srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+user,'Spam_BuildFakersList','');
        
    }
    
    this.NotSpam = function(result,user)
    {
        var mes = new Messages();
        var ma = new Array();
        var srv = new Server();
        
        if (result.code == 201)
        {
            ma[0]='User successfully marked as not spam.';
            mes.Build('success',ma,'.header');
        }
        else
        {
            ma[0]='Failed to mark user as not spam, please try again.';
            mes.Build('failure',ma,'.header');
        }
        
        srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+user,'Spam_BuildFakersList','');
        
    }
	
	this.ProcessUserCheck = function(result)
	{
		var pop = new Popup();
		
		if (result.code == 500)
		{
			pop.BuildRightInfoBox();
			var p = $('<p><strong>Get 5 Free Friend Searches.</strong></p>'+
								 '<p>Just give us a few details.</p>'+
								 '<input type="button" id="freesearches" value="Free Searches" />'+
								 '<p><strong>Get Unlimited Friend Searches</strong></p>'+
								 '<p>Sign up for a subscription.</p>'+
								 '<a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a>');
			pop.RightInfoContent(p);
		}
		else if (result.code == 201)
		{
			pop.BuildRightInfoBox();
			var p = $('<p><strong>Get Unlimited Friend Searches</strong></p>'+
								 '<p>Sign up for a subscription.</p>'+
								 '<a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a>');
			pop.RightInfoContent(p);
		}
	}
	
	this.ProcessUserAddDetails = function(result)
	{
		var pop = new Popup();
		var ms = new Messages();
		
		if (result.code == 201)
		{
			ms.Build('success',['User details added successfully. And you now have 5 extra friend searches. If you want unlimited searches <a href="/Payments/Subscriptions">sign up for a subscription</a>.'],'.header');
			pop.RemovePopup();
			$.cookie('searches',result.data.searches,1000);
			$("#friendsearches").text(result.data.searches);
			pop.RightInfoClose();
			pop.BuildRightInfoBox();
			var p = $('<p><strong>Get Unlimited Friend Searches</strong></p>'+
								 '<p>Sign up for a subscription.</p>'+
								 '<a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a>');
			pop.RightInfoContent(p);
		}
		else if (result.code == 500)
		{
			ms.Build('failure',['Failed to create user.'],'.header');
			pop.RemovePopup();
		}
		else
		{
			pop.AddMessage(result.message,true);
		}
		
		pop.RemoveTinyLoader();
	}
}

function Tweets()
{
    
    this.BuildTweet = function(tweet)
    {
        var item = $('<li/>');
                
        var img = $('<img/>');
        img.attr('src',tweet.avatar);
        img.attr('height','48px');
        img.attr('width','48px');

        img.appendTo(item);

        var p1 = $('<p/>');
        var tw = this.HashAtLink(tweet.tweet);

        p1.append('<strong>'+tweet.name+'</strong> ');

        p1.append(tw);

        p1.appendTo(item);

        var p2 = $('<small/>');
        p2.addClass('orange');
        p2.html('Source: '+ tweet.source +' | Date: '+tweet.date);

        p2.appendTo(item);
        
        return item;
    }
    
    this.HashAtLink = function(tweet)
    {
        var rg1 = new RegExp('https?:\\/\\/([a-zA-Z0-9\\/\\_\\-\\.\\(\\)\\&\\?\\=]*)','g');
        var rg2 = new RegExp('^@([a-zA-Z0-9_-]{1,})','g');
        var rg3 = new RegExp('\\s@([a-zA-Z0-9_-]{1,})','g');
        var rg4 = new RegExp('^#([a-zA-Z0-9_-]{1,})','g');
        var rg5 = new RegExp('\\s#([a-zA-Z0-9_-]{1,})','g');
        
        var tw = tweet.replace(rg1,'<a href="http://$1" target="_blank" class="createdlink">$1</a>');
        tw = tw.replace(rg2,'<a href="http://twitter.com/$1" class="tweetuser" target="_blank">@$1</a>');
        tw = tw.replace(rg3,' <a href="http://twitter.com/$1" class="tweetuser" target="_blank">@$1</a>');
        tw = tw.replace(rg4,' <a href="http://twitter.com/search?q=%23$1" class="tweethash" target="_blank">#$1</a>');
        tw = tw.replace(rg5,' <a href="http://twitter.com/search?q=%23$1" class="tweethash" target="_blank">#$1</a>');
        
        
        return tw;
    }
    
    this.GetAtUsers = function(txt)
    {
        var rg1 = new RegExp('\\s@([a-zA-Z0-9_-]{1,})','g');
        
        var mtchs = txt.match(rg1);
        
        return mtchs;
    }
    
    this.CreateRetweet = function(txt)
    {
        var rg1 = new RegExp('^([a-zA-Z0-9_-]{1,})\\s');
        var rg2 = new RegExp('([a-zA-Z0-9]{1,}\\.[a-zA-Z0-9]{1,}\\/[a-zA-Z0-9]{1,})','g');
        
        var mtch = txt.match(rg1);
        
        var replace = 'RT @'+mtch[1]+': ';
        
        var newtxt = txt.replace(rg1,replace);
        newtxt = newtxt.replace(rg2,'http://$1');
        
        return newtxt;
    }
    
    this.BuildUser = function(result)
    {
        var li = $('<li/>');
        
        var div = $('<div/>');

        var img = $('<img/>');
        img.attr('width','48');
        img.attr('height','48');
        img.attr('src',result.image);
        img.appendTo(div);

        var p1 = $('<p/>'); 
        p1.html(result.screenname+'<br/>'+result.location+'<br/><a href="'+result.url+'" target="_blank">'+result.url+'</a><br/>Tweets: '+result.tweets+'<br/>Followers: '+result.followers+' <a href="http://twitter.com/'+result.screenname+'" class="tweetfollowers">View</a><br/>Friends: '+result.friends+'<br/>Days Active: '+result.daysactive);
        p1.appendTo(div);

        var p2 = $('<p/>'); 
        p2.attr('class','kred');
        p2.appendTo(div);

        var srv = new Server();
        srv.CallServer('GET','json','/API/GetKredScore','rf=json&usr='+result.screenname,'Tweets_AddKredScore',p2);
        
        var p3 = $('<p/>'); 
        p3.text(result.description);
        p3.appendTo(div);

//        var following = (result.following==1)?'Following':'<a href="/SocialMedia/FollowTweeter/V/'+result.id+'/'+result.screenname+'">Follow</a>';

        var p4 = $('<p/>');
        p4.html('<a href="http://twitter.com/'+result.screenname+'" class="usertweettimeline">Timeline</a>');
        p4.appendTo(div);
        
        div.appendTo(li);
        
        return li;
    }
    
    this.BuildDM = function(DM)
    {
        
    }
    
    this.BuildRetweet = function(retweet)
    {
        
    }
    
    this.AddKredScore = function(result,p2)
    {
        if (result.code == 201)
        {
            p2.html('<img src="/Pie/Crust/Template/img/Kred_Logo.png" /> '+result.data.influence+'/'+result.data.outreach);
        }
        else
        {
            p2.html('<img src="/Pie/Crust/Template/img/Kred_Logo.png" /> N/A');
        }
    }
    
    this.Timeline = function(result,id)
    {
        var tw = new Tweets();
        var hub = new Hub();
        var listid = '#twitter'+id;
        var holderid = '#timelineholder'+id;
        
        if (result.code == 201)
        {
            var c = 0;
            
            if ($(listid).length)
            {
                $.each(result.data,function(i,twt){
                    
                    if (!c)
                    {
                        $('#sinceid'+id).val(twt.id);
                    }
                    
                    var tweet = tw.BuildTweet(twt);
                    tweet.prependTo(listid);
                    c=c+1;
                    
                });
            }
            else
            {
                var ul = $('<ul/>');
                ul.attr('id','twitter'+id);
                ul.addClass('twittertimeline');
                
                $.each(result.data,function(i,twt){
                    
                    if (!c)
                    {
                        $('#sinceid'+id).val(twt.id);
                    }
                    
                    var tweet = tw.BuildTweet(twt);
                    tweet.appendTo(ul);
                    c=c+1;
                    
                });
                
                ul.appendTo(holderid);
            }
        }
        else
        {
            if ($(listid).length)
            {
                //Do Nothing
            }
            else
            {
                var pop = new Popup();
                pop.Loader();
                pop.AddMessage('No data returned',true);
            }
        }
        
        if ($('#viewnewtweets').length)
        {
            $('#viewnewtweets').remove();
        }
        
        hub.Loader('');
    }

    this.Followers = function(result)
    {
        var pop = new Popup();
        var tw = new Tweets();
        
        if (result.code == 201)
        {
            var ul = $('<ul/>');
            ul.addClass('twitterusers');
            
            $.each(result.data,function(i,u){
               
               var usr = tw.BuildUser(u);
               usr.appendTo(ul);
               
            });
            
            pop.Content(ul,'popupscroll');
        }
        else
        {
            pop.AddMessage('No follower data returned at this time.',true);
        }
            
        pop.RemoveLoader();
    }

    this.NewCount = function(result)
    {

        if (result.code == 201)
        {
            var hub = new Hub();
            var c = 0;
            
            $.each(result.data,function(i,r)
            {
                c += 1;
            });

            if ($('#viewnewtweets').length)
            {
                $('#viewnewtweets').text('You have '+c+' new Tweets to view.');
            }
            else
            {
                var div = $('<div/>');
                div.attr('id','viewnewtweets');
                div.text('You have '+c+' new Tweets to view.');
                div.insertBefore('#timelineholderhome')
            }
        }
        
        hub.Loader('');
    }
    
    this.BuildUserTimeline = function(result)
    {
        var tw = new Tweets();
        var pop = new Popup();
        
        pop.RemoveLoader();
        pop.RemoveMessage();
        
        if (result.code == 201)
        {
            var ul = $('<ul/>');
            
            $.each(result.data,function(i,t){
                var tweet = tw.BuildTweet(t);
                tweet.prependTo(ul);
            });
            
            pop.Content(ul);
        }
        else
        {
            pop.AddMessage('No tweets found for this user', true);
        }
            
    }
    
}