function Charts()
{   
	this.Chart = function(graph,data,exportbutton)
    {
        options = {
            chart: {
                renderTo: data[0].id,
                defaultSeriesType: data[0].type,
                height: data[0].height,
                backgroundColor: data[0].backgroundcolor,
                zoomType: data[0].zoom
            },
            title: {
                text: data[0].title,
                x: 0, //center
                align:'center',
                style: {color: '#fe7d1d',fontSize:'16px'}
            },
            subtitle: {
                text: data[0].subtitle,
                x: 0,
                style: { color: '#36b6d5' }
            },
            xAxis: {
                title: {
                        text: data[0].xtitle,
                        style: { color: '#444' }
                    },
                categories: [],
                labels: {
                    enabled: data[0].xenabled
                },
                reversed:data[0].xreverse
            },
            yAxis: {
                title: {
                    text: data[0].ytitle,
                    style: {color: '#36b6d5'}
                },
                plotLines: [{
                    value: 0,
                    width: 1
                }],
                min:data[0].ymin,
                offset: data[0].yoffset,
                showFirstLabel:data[0].yfirstlabel
            },
            tooltip:{
                enabled:true,
                formatter: function(){
                    if (this.series.chart.options.chart.defaultSeriesType == 'pie')
                    {
                        return '<b>'+this.point.name+':</b> '+Highcharts.numberFormat(this.percentage,1)+'%';
                    }
                    else if (this.series.chart.options.chart.defaultSeriesType == 'column')
                    {
                        return 'Hour: ' + this.x + ' | Count: ' + this.y;
                    }
                    else
                    {
                        return this.series.name + ': ' + this.y;
                    }
                }
            },
            legend: {
                enabled: data[0].legendenabled,
                borderWidth:0
            },
            plotOptions:{
                pie:{
                    showInLegend:true,
                    dataLabels: {
                        enabled: false
                    }
                },
                column:{
                    stacking:'normal'
                }
            },
            colors: data[0].colors,
            series: []
        };
        
        if (data[0].multi && (data[0].type == 'line'||data[0].type == 'column'))
        {
            $.each(graph.data, function (i, result) {

                var interactions = {
                    data: []
                };

                interactions.name = i;

                $.each(result, function (i, grdata) {

                    options.xAxis.categories.push(grdata.date);
                    interactions.data.push(parseFloat(grdata.count));

                });

                options.series.push(interactions);

            });
        }
        else if (data[0].type == 'pie')
        {
            options.series = [{
                type: 'pie',
                name: data[0].xtitle
            }]; 

            var interactions = {
                data: []
            };

            $.each(graph.data, function (i, result) {

                interactions.data.push({ name: result.name, y: parseFloat(result.count) });

            });
            
            options.series.push(interactions);
        }
        else
        {
            var interactions = {
                data: []
            };
            
            interactions.name = data[0].interactions;
            
            $.each(graph.data, function (i, result) {

                options.xAxis.categories.push(result.date);
                interactions.data.push(parseFloat(result.count));

            });
            
            options.series.push(interactions);
        }
        
        var chart = new Highcharts.Chart(options);
        
        if (exportbutton)
        {
            this.ExportButton(exportbutton);
        }
    }
    
    this.BuildChart = function(result,data)
    {
        
        if (result.code == 201)
        {
            var chart;
            
            if (data[0].size=='small')
            {
                chart = [{
                        id:data[0].id,
                        type:data[0].type,
                        multi:data[0].multi,
                        height:200,
                        backgroundcolor:data[0].backgroundcolor,
                        zoom:'',
                        title:'',
                        subtitle:'',
                        xtitle:'',
                        xreverse:data[0].xreverse,
                        xenabled:data[0].xenabled,
                        ytitle:'',
                        ymin:'',
                        yoffset:-40,
                        yfirstlabel:false,
                        legendenabled:false,
                        interactions:data[0].interactions,
                        colors:data[0].colors
                }]
            }
            else
            {
                chart = [{
                        id:data[0].id,
                        type:data[0].type,
                        multi:data[0].multi,
                        height:null,
                        backgroundcolor:data[0].backgroundcolor,
                        zoom:'x',
                        title:data[0].title,
                        subtitle:data[0].subtitle,
                        xtitle:'Date',
                        xreverse:data[0].xreverse,
                        xenabled:true,
                        ytitle:'',
                        ymin:'',
                        yoffset:'',
                        yfirstlabel:true,
                        legendenabled:true,
                        interactions:data[0].interactions,
                        colors:data[0].colors
                }]
            }
            
            this.Chart(result,chart,data[1]);
            
        }
        else
        {
            $('#'+data[0].id).html('<p>No Data Returned</p>');
        }
		
		var pop = new Popup();
		pop.RemoveTinyLoader();
       
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
    
    this.RecalculateCart = function(currency,months,type)
    {
        
        var cur = '&pound;';
        var base = 3.49;
		var tax = 0.70;
        
		if (type==2)
		{
			base = 9.99;
			tax = 1.98;
		}
		else if (type==3)
		{
			base = 99.99;
			tax = 19.98;
		}
		
        if (currency == 'USD')
        {
            cur = '&#36;';
            base = 5.49;
			tax = 0.00;
			
			if (type==2)
			{
				base = 14.99;
				tax = 0.00;
			}
			else if (type==3)
			{
				base = 149.99;
				tax = 0.00;
			}
        }
        else if (currency == 'EUR')
        {
            cur = '&euro;';
            base = 4.49;
            tax = 0.90;
			
			if (type==2)
			{
				base = 12.99;
				tax = 2.60;
			}
			else if (type==3)
			{
				base = 129.99;
				tax = 26.99;
			}
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
	
	this.AddContent = function(data,style)
    {
        data.appendTo('#popupcontent');
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
            },
			error: function(){
				pop = new Popup();
				pop.RemoveTinyLoader();
				ms = new Messages();
				ms.Build('failure',['An error occurred: Process Failed!'],'.header');
			}
			
        });
    }

}

function Spam()
{
	
	this.ProcessUserAddSite = function(result)
	{
		var pop = new Popup();
		var ms = new Messages();
		
		if (result.code == 201)
		{
			ms.Build('success',['Thanks for suggesting an address to add to our Fakers Site Directory.'],'.header');
			$('#fakersite').val('');
		}
		else
		{
			ms.Build('failure',[result.message],'.header');		
		}
		
		pop.RemoveTinyLoader();
	}
	
	this.ProcessSiteData = function(result,id)
	{
		if (result.code == 201)
		{
			id.children('fieldset').children('.siteimage').val(result.data.image.src);
			id.children('fieldset').children('.sitedescription').val(result.data.para);
		}
	}
    
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
            spam.attr('class','three center');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            if (source==1)
            {
                $('#spamscore').val(spamscore);
            }
            
            var inactive = $('<div/>');
            inactive.attr('class','three center');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three center');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
            spam.appendTo('#scoresholder');
            inactive.appendTo('#scoresholder');
            good.appendTo('#scoresholder');
            
            var div2 = $('<div/>');
            div2.attr('class','one');
            div2.attr('id','shareform');
            
            var button = $('<input/>');
            button.attr('type','button');
            
            if (source==1||source==3)
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
			
			var buycount = parseInt($.cookie('buycount'));
			
			if (spamscore>=10&&buycount!=1)
			{
				var pop = new Popup();
				
				pop.BuildPopup();
				
				var buy = $('<p class="red sp2 sf2 center">You have a lot of Fake Followers.</p>'+
							'<p class="sp2 sf2 center"><a href="/Payments/Subscriptions?type=2">Purchase a Subscription to Automatically Remove Them.</a></p>');
				
				pop.Content(buy);
				
				$.cookie('buycount',1,1);
			}
			
			if (!$('#extrainfo').length&&source!=3)
			{
				var langcount = 0;
				var langname = 'English';
				
				if (result.data.lang!=null)
				{
					langcount = result.data.lang.count;
					var langname = result.data.lang.name;
				}
				
				var huncount = 0;
				
				if (result.data.hundred!=null)
				{
					huncount = result.data.hundred;
				}
				
				var fr250count = 0;
				
				if (result.data.fr250!=null)
				{
					fr250count = result.data.fr250;
				}
				
				var langper = Math.round((langcount/result.data.checks)*100);
				var hunper = Math.round((huncount/result.data.checks)*100);
				var fr250 = Math.round((fr250count/result.data.checks)*100);
				var exinf = $('<div id="extrainfo" class="textleft row"></div>');
				var extra = $('<div class="two a"><h2>Your Followers</h2>'
							  +'<p class="f2 sp2 blue">'+langper+'% speak '+langname+'</p>'
							  +'<p class="f2 sp2 blue">'+hunper+'% have not tweeted in 100 days</p>'
							  +'<p class="f2 sp2 blue">'+fr250+'% follow less than 250 people</p></div>');
				
				var fakers = '';
				
				if (result.data.spam1!=null&&result.data.spam2==null)
				{
					fakers = $('<div class="two"><h2>Your Fakers</h2>'
									+'<ul class="fakeslist">'
									+'<li><img src="'+result.data.spam1.image+'" height="48px" width="48px"/> <span class="red">'+result.data.spam1.screen_name+'</span><small><a href="#details" class="details" data-sc="'+result.data.spam1.screen_name+'">Details</a></small></li>'
									+'</ul></div>');
				}
				else if (result.data.spam1!=null&&result.data.spam2!=null)
				{
					fakers = $('<div class="two"><h2>Your Fakers</h2>'
									+'<ul class="fakeslist">'
									+'<li><img src="'+result.data.spam1.image+'" height="48px" width="48px"/> <span class="red">'+result.data.spam1.screen_name+'</span><small><a href="#details" class="details" data-sc="'+result.data.spam1.screen_name+'">Details</a></small></li>'
									+'<li><img src="'+result.data.spam2.image+'" height="48px" width="48px"/> <span class="red">'+result.data.spam2.screen_name+'</span><small><a href="#details" class="details" data-sc="'+result.data.spam2.screen_name+'">Details</a></small></li>'
									+'</ul></div>');
				}
				else
				{
					fakers = $('<div class="two"><h2>Your Fakers</h2>'
								+'<p class="f2 sp2 green">Yay!! You have no Fake Followers</p></div>');
				}
				
				var subscrp = $('<div class="row"><div class="one center f2 sp2"><a href="/Payments/Details" class="orange">Learn more about your Followers &mdash; Purchase a Subscription</a></div></div>');
				
				subscrp.appendTo(exinf);
				extra.appendTo(exinf);
				fakers.appendTo(exinf);
				
				exinf.insertAfter('#SearchForm');
			}
        }
        else
        {
            
			var div = $('<div class="one"></div>');
            var h1 = $('<h1/>');
            var s = $('<small/>');
            
            if (result.code == 429)
            {
                h1.text('We are sorry but you have breached your Twitter API 1.1 Limit.');
                h1.appendTo(div);
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                s.appendTo(div);
            }
            else
            {
                h1.text('No data returned for this user.');
                h1.appendTo(div);
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                s.appendTo(div);
            }
            
			div.appendTo('#scoresholder');
        }
        
        //$('#loader').hide('slow').remove();
        
        $('#searchsubmit').removeAttr('disabled');
		
		var pop = new Popup();
		pop.RemoveTinyLoader();
        
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
            spam.attr('class','three center');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            var inactive = $('<div/>');
            inactive.attr('class','three center');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three center');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
            spam.appendTo('#scoresholder');
            inactive.appendTo('#scoresholder');
            good.appendTo('#scoresholder');
            
            var div2 = $('<div/>');
            div2.attr('class','one');
            div2.attr('id','shareform');
            
            var button = $('<input/>');
            button.attr('type','button');
            
            button.attr('id','resetscores');
            button.val('Display Your Scores');    
                
            var srv = new Server();
            srv.CallServer('GET','JSON','/API/GetCompetitorCount','rf=json&usr='+encodeURIComponent(usr),'Spam_AddCompetitorButton');
            
            button.appendTo(div2);
            
            div2.insertAfter('#scoresholder');
                        
            $('#spam').val(result.data.spam);
            $('#potential').val(result.data.potential);
            $('#checks').val(result.data.checks);
            $('#followers').val(result.data.followers);
        }
        else
        {
			var div = $('<div class="one"></div>');
            var h1 = $('<h1/>');
            var s = $('<small/>');
            
            if (result.code == 429)
            {
                h1.text('We are sorry but you have breached your Twitter API 1.1 Limit.');
                h1.appendTo(div);
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                s.appendTo(div);
            }
            else
            {
                h1.text('No data returned for this user.');
                h1.appendTo(div);
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                s.appendTo(div);
            }
			
			div.appendTo('#scoresholder');
        }
        
        //$('#loader').hide('slow').remove();
        
        $('#searchsubmit').removeAttr('disabled');
		
		var pop = new Popup();
		pop.RemoveTinyLoader();
        
    }
    
	this.ProcessSpamDataPopup = function(result,usr)
    {
		var pop = new Popup();
		
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
            
			var name = $('<h2 id="friendsearchname">'+$('#friendsearchquery').val()+'</h2>');
			
            var spam = $('<div/>');
            spam.attr('class','three center');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            var inactive = $('<div/>');
            inactive.attr('class','three center');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three center');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
			var div2 = $('<div/>');
			
			name.appendTo(div2);
            spam.appendTo(div2);
            inactive.appendTo(div2);
            good.appendTo(div2);
            
            pop.Content(div2);
            
			var srv = new Server();
            srv.CallServer('GET','JSON','/API/GetCompetitorCount','rf=json&usr='+encodeURIComponent(usr),'Spam_AddCompetitorButtonPopup');
			
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
                pop.Content(h1);
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                pop.AddContent(s);
            }
            else
            {
                h1.text('No data returned for this user.');
                pop.Content(h1);
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                pop.AddContent(s);
            }
        }
        
        pop.RemoveTinyLoader();
    }
	
    this.ProcessCachedSpamData = function(result)
    {
        if (result.code == 201)
        {
            var spamscore = result.data.Fake[0].count;
            var goodscore = result.data.Good[0].count;
            var potentialscore = result.data.Inactive[0].count;
            
            var spam = $('<div/>');
            spam.attr('class','three center');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            $('#spamscore').val(spamscore);
            
            var inactive = $('<div/>');
            inactive.attr('class','three center');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three center');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
            spam.appendTo('#scoresholder');
            inactive.appendTo('#scoresholder');
            good.appendTo('#scoresholder');
            
            var div2 = $('<div/>');
            div2.attr('class','one');
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
			var div = $('<div class="one"></div>');
            var h1 = $('<h1/>');
            var s = $('<small/>');
            
            if (result.code == 429)
            {
                h1.text('We are sorry but you have breached your Twitter API 1.1 Limit.');
                h1.appendTo(div);
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                s.appendTo(div);
            }
            else
            {
                h1.text('No data returned for this user.');
                h1.appendTo(div);
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                s.appendTo(div);
            }
			
			div.appendTo('#scoresholder');
        }
        
        //$('#loader').hide('slow').remove();
		
		var pop = new Popup();
		pop.RemoveTinyLoader();
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
            spam.attr('class','three center');
            spam.html('<h1 class="red">Fake</h1><h2 class="red">'+spamscore+'%</h2>');
            
            var inactive = $('<div/>');
            inactive.attr('class','three center');
            inactive.html('<h1>Inactive</h1><h2>'+potentialscore+'%</h2>');
                        
            var good = $('<div/>');
            good.attr('class','three center');
            good.html('<h1 class="green">Good</h1><h2 class="green">'+goodscore+'%</h2>');
            
            spam.appendTo('#scoresholder');
            inactive.appendTo('#scoresholder');
            good.appendTo('#scoresholder');
            
            var div2 = $('<div/>');
            div2.attr('class','one');
            div2.attr('id','shareform');
            
            var srv = new Server();
            
            srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+encodeURIComponent(usr),'Spam_BuildFakersList','');
            
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
			var div = $('<div class="one"></div>');
            var h1 = $('<h1/>');
            var s = $('<small/>');
            
            if (result.code == 429)
            {
                h1.text('We are sorry but you have breached your Twitter API 1.1 Limit.');
                h1.appendTo(div);
                
                s.html('Please wait 15 minutes and try again. For more information on Twitter API limits please read their <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">rate limiting policies</a> or contact info@statuspeople.com.');
                s.appendTo(div);
            }
            else
            {
                h1.text('No data returned for this user.');
                h1.appendTo(div);
            
                s.html('If you are having any persistant problems accessing data with StatusPeople please <a href="/Fakers/Reset">reset your connection details</a>.');
                s.appendTo(div);
            }
			
			div.appendTo('#scoresholder');
        }
        
        //$('#loader').hide('slow').remove();
		
		var pop = new Popup();
		pop.RemoveTinyLoader();
        
    }
    
    this.ProcessSpamScoreShare = function(result)
    {
        //var h2 = $('<h2/>');
        pop = new Popup();
		var ms = new Messages();
		
        if (result.code==201)
        {
            //h2.text('Scores shared to Twitter.');
			ms.Build("success",["Scores shared to Twitter."],".header");
        }
        else
        {
            //h2.text('Failed to share scores to Twitter.');
			ms.Build("failure",["Failed to share scores to Twitter."],".header");
        }
        
        //$('#sharing').remove();
		
		pop.RemoveTinyLoader();
        //h2.appendTo('#shareform');
    }
	
	this.ProcessCacheData = function(result)
	{
		if (result.code == 201)
		{
			var data = new Object();
			data.code = 201;
			data.data = result.data.lang
		
			var chr = new Charts();
			chr.BuildChart(data,[{'id':'langchart','type':'pie','multi':false,'size':'large','xreverse':false,'backgroundcolor':'#fefefe','colors':['#FE7D1D','#36B6D5','#2AFE1B','#FE1B2A','#7D1BFE']}]);
			
			this.BuildAverages(result.data.avg);
			
		}
		else
		{
			var ms = new Messages();
			ms.Build("failure",["No language or average data was returned."],".header");
		}
		
		var pop = new Popup();
		pop.RemoveTinyLoader();
	}
    
	this.BuildAverages = function(data)
	{
		if ($("#averages").length)
		{
			$("#averages").remove();
		}
		
		var frm = new Format();
	
		var table = $('<table id="averages"/>');
		var tr1 = $('<tr><th>Activity</th><th>Followers</th><th>Friends</th></tr>');
		var tr2 = $('<tr><td>'+Math.round(data.tweets_pd/data.count)+' Tweets per day</td><td>'+frm.Number(Math.round(data.followers/data.count))+' on average</td><td>'+frm.Number(Math.round(data.friends/data.count))+' on average</td></tr>');
		var tr3 = $('<tr><td>'+Math.round((data.one/data.count)*100)+'% no tweets in last day</td><td>'+Math.round((data.fo250/data.count)*100)+'% have less than 250</td><td>'+Math.round((data.fr250/data.count)*100)+'% follow less than 250</td></tr>');
		var tr4 = $('<tr><td>'+Math.round((data.thirty/data.count)*100)+'% no tweets in 30 days</td><td>'+Math.round((data.fo500/data.count)*100)+'% have about 500</td><td>'+Math.round((data.fr500/data.count)*100)+'% follow about 500</td></tr>');
		var tr5 = $('<tr><td>'+Math.round((data.hundred/data.count)*100)+'% no tweets in 100 days</td><td>'+Math.round((data.fo1000/data.count)*100)+'% have more than 1,000</td><td>'+Math.round((data.fr1000/data.count)*100)+'% follow more than 1,000</td></tr>');
		 
		tr1.appendTo(table);
		tr2.appendTo(table);
		tr3.appendTo(table);
		tr4.appendTo(table);
		tr5.appendTo(table);
		
		table.appendTo("#followerdata");
		
	}
	
    this.Sharing = function()
    {
        $('#sharescores').remove();
        
        // var loader = $('<div/>');
        // loader.attr('id','sharing');
        
        // var img = $('<img/>');
        // img.attr('src','/Pie/Crust/Template/img/287.gif');
        // img.attr('id','imageloader');
        
        // img.appendTo(loader);
        
        // loader.appendTo('#shareform');  

		var pop = new Popup();
		pop.TinyLoader();
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
            input.attr('value','Add User To Friends List');
            
            if (result.data.competitors >= 6 && result.data.type == 1)
            {
                input.attr('disabled','disabled');
                ma[0]='You cannot add any more users to your fakers list.';
                mes.Build('alert',ma,'.header');
            }
			else if (result.data.competitors >= 16 && result.data.type == 2)
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
	
	this.AddCompetitorButtonPopup = function(result)
    {
        var mes = new Messages();
        var ma = new Array();
		var pop = new Popup();
        
        if (result.code == 201)
        {
			var input = $('<form><fieldset><input type="button" id="addfakerpopup" value="Add User To Friend List"/></fieldset></form>');
            
			pop.AddContent(input);
			
			if (result.data.competitors >= 6 && result.data.type == 1)
            {
                $('#addfakerpopup').attr('disabled','disabled');
                pop.AddMessage('You cannot add any more users to your fakers list.',true);
            }
			else if (result.data.competitors >= 16 && result.data.type == 2)
			{
				$('#addfakerpopup').attr('disabled','disabled');
                pop.AddMessage('You cannot add any more users to your fakers list.',true);
			}        
			
		}
        else
        {
            pop.AddMessage('Could not find user details on Twitter please try your search again.',true);
        }
        
    }
    
    this.AddFaker = function(result,user)
    {
        var mes = new Messages();
        var ma = new Array();
        var srv = new Server();
        
        if (result.code == 201)
        {
            ma[0]='User added to <a href="/Fakers/Followers">Friends List</a>.';
            mes.Build('success',ma,'.header');
        }
        else
        {
            ma[0]='Failed to add user to Friends List, please try again';
            mes.Build('failure',ma,'.header');
        }
        
        srv.CallServer('GET','json','/API/GetCompetitorList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildCompetitorList','');
        
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
            //ma[0]='Failed to remove user from Fakers List, please try again';
            mes.Build('failure',[result.message],'.header');
        }
        
        srv.CallServer('GET','json','/API/GetCompetitorList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildCompetitorList','');
        
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
				tr.html('<td><a href="http://twitter.com/'+r.screen_name+'" target="_blank"><img src="'+r.avatar+'" width="36px" height="36px" /></a></td><td><span class="blue pointer details" data-sc="'+r.screen_name+'">'+r.screen_name+'</span></td><td><span class="red">Fake: '+fake+'%</span></td><td><span class="orange">Inactive: '+inactive+'%</span></td><td><span class="green">Good: '+good+'%</span></td><td><input type="hidden" value="'+r.twitterid+'" class="ti"/><input type="hidden" value="'+r.screen_name+'" class="sc"/><span class="chart icon" data-tip="View on chart"><img src="/Pie/Crust/Template/img/Reports.png" height="24px" width="22px"/></span></td><td><input type="hidden" value="'+r.twitterid+'"/><span class="delete icon" data-tip="Remove">X</span></td>');
                tr.appendTo(tbl);
            });
            
            tbl.appendTo('#fakerslist');
        }
        
    }
    
    this.BuildFakersList = function(result)
    {
        var pop = new Popup();
		pop.RemoveTinyLoader();
        
        if (result.code == 201)
        {
			if ($('#checkform').length)
		   	{
			   $('#checkform').remove();
		   	}
			
            if ($('#spammers .fakeslist').length)
            {
                $('#spammers .fakeslist').remove();
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
            if ($('#spammers .fakeslist').length)
            {
                $('#spammers .fakeslist').remove();
            }
            
			if (!$('#checkform').length)
			{
			
				var div = $('<div id="checkform"/>');
				
				var p = $('<p/>');
				p.text('No Fake Followers found at this time.');
				
				p.appendTo(div);
				
				var f = $('<p><fieldset><input type="button" id="checkfakes" value="Check For New Fakes"/></fieldset></p>');
				
				f.appendTo(div);
				
				div.appendTo('#spammers');
			}
			
			var ms = new Messages();
			ms.Build('alert',['No Fake Followers found at this time, please try again later.'],'.header');
        }
    }
	
	this.BuildBlockedList = function(result)
    {
        var pop = new Popup();
		pop.RemoveTinyLoader();
        
        if (result.code == 201)
        {
            if ($('#blocked .fakeslist').length)
            {
                $('#blocked .fakeslist').remove();
            }
            
            var ul = $('<ul class="fakeslist"/>');
            
            $.each(result.data,function(i,f){
                
                var li = $('<li/>');
                li.html('<input type="hidden" value="'+f.screen_name+'" class="sc" /><input type="hidden" value="'+f.twitterid+'" class="ti"/><img src="'+f.avatar+'" width="48px" height="48px" /> '+f.screen_name+'<small><a href="#details" class="details">Details</a> | <a href="#block" class="unblock">Unblock</a>');
                li.appendTo(ul);
                
            });
            
            ul.appendTo('#blocked');
        }
    }
    
    this.UpdateFakersList = function(result,user)
    {
        
        var srv = new Server();
        
        if (result.code == 201)
        {
            srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildFakersList','');
        }
        else
        {
            if (result.code==429)
            {
                //pop.Loader();
                //var pop = new Popup();
                var ms = new Messages();
				ms.Build('alert',result.message,'.header');
            }
            
            srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildFakersList','');
            
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
		var fn = new Format();
        
        if (result.code == 201)
        {
            ma[0]='User blocked successfully.';
            mes.Build('success',ma,'.header');
			$('#blockcount').text(fn.Number(result.data));
        }
        else
        {
            ma[0]='Failed to block user, please try again.';
            mes.Build('failure',ma,'.header');
        }
        
        srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildFakersList','');
		srv.CallServer('GET','json','/API/GetBlockedList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildBlockedList','');
        
    }
	
	this.UnBlockUser = function(result,user)
    {
        var mes = new Messages();
        var ma = new Array();
        var srv = new Server();
        
        if (result.code == 201)
        {
            ma[0]='User unblocked successfully.';
            mes.Build('success',ma,'.header');
        }
        else
        {
            ma[0]='Failed to unblock user, please try again.';
            mes.Build('failure',ma,'.header');
        }
        
        srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildFakersList','');
		srv.CallServer('GET','json','/API/GetBlockedList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildBlockedList','');
        
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
        
        srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+encodeURIComponent(user),'Spam_BuildFakersList','');
        
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
								 '<a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a>'+
					  			'<p><strong>Auto Block</strong></p>'+
					 			'<p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p>'+
					  			'<form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');
			pop.RightInfoContent(p);
		}
		else if (result.code == 201)
		{
			pop.BuildRightInfoBox();
			var p = $('<p><strong>Get Unlimited Friend Searches</strong></p>'+
								 '<p>Sign up for a subscription.</p>'+
								 '<a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a>'+
					  		'<p><strong>Auto Block</strong></p>'+
					 		'<p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p>'+
					  		'<form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');
			pop.RightInfoContent(p);
		}
	}
	
	this.EventbriteData = function(result)
	{
		var pop = new Popup();
		
		if (result.code == 201)
		{
			//alert(result.data[0].id);
			
			pop.BuildRightInfoBox();
			var p = $('<p><strong>Join Our Next Webinar</strong></p>'+
								 '<p>'+result.data[0].title+'</p>'+
					  			 '<input type="button" id="webinarbut" data-link="'+result.data[0].link+'" value="Register Now" />');
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
								 '<a href="/Payments/Details"><input type="button" value="Unlimited Searches"/></a>'+
					 			'<p><strong>Auto Block</strong></p>'+
					 		'<p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p>'+
					  		'<form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');
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
	
	this.ProcessFakerFind = function(result)
	{
		if ($('#blocksearchdata .fakeslist').length)
		{
			$('#blocksearchdata .fakeslist').remove();
		}
		
		var ul = $('<ul class="fakeslist"/>');
		
		if (result.code = 201)
		{
			$.each(result.data,function(i,f){
                
                var li = $('<li/>');
                li.html('<input type="hidden" value="'+f.screen_name+'" class="sc" /><input type="hidden" value="'+f.twitterid+'" class="ti"/><img src="'+f.avatar+'" width="48px" height="48px" /> '+f.screen_name+'<small><a href="#details" class="details">Details</a> | <a href="#block" class="unblock">Unblock</a>');
                li.appendTo(ul);
                
            });
		}
		else
		{
			var li = $('<li>No Blocked Followers found.</li>');
			li.appendTo(ul);
		}
		
		ul.appendTo('#blocksearchdata');
	}
	
	this.AutoBlockUpdate = function(result)
	{
		var ms = new Messages();
		var pop = new Popup();
		
		if (result.code == 201)
		{
			var span;
			var span2;
			
			if (result.data==1)
			{
				ms.Build('success',['Auto blocking has been turned on.'],'.header');
				span = $('<span class="green">On <span class="ico">;</span></span>');
				span2 = $('<span id="autooff" class="microbutton pointer">Turn Off</span>');
			}
			else
			{
				ms.Build('success',['Auto blocking has been turned off.'],'.header');
				span = $('<span class="red">Off <span class="ico" style="font-size:20px;">9</span></span>');
				span2 = $('<span id="autoon" class="microbutton pointer">Turn On</span>');
			}
			
			var h2 = $('<h2>Auto Block Fakes </h2>');
			span.appendTo(h2);
			$('#autoblock.one h2 + span').remove();
			$('#autoblock.one h2').remove();
			h2.appendTo('#autoblock.one');
			span2.appendTo('#autoblock.one');
		}
		else
		{
			ms.Build('failure',['Failed to change your auto blocking status.'],'.header');
		}
		
		pop.RemovePopup();
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
    
	this.BuildUser = function(result,gk)
    {
        var li = $('<li/>');
        
        var div = $('<div class="userholder"/>');

        var div1 = $('<div/>');

        var img = $('<img width="48" height="48" src="'+result.image+'"/>');
        img.appendTo(div1);
        
        var fkclass = 'green';
        
        if (result.spam == 'Inactive')
        {
            fkclass = 'orange';
        }
        else if (result.spam == 'Fake')
        {
            fkclass = 'red';
        }
        
        var fake = $('<div class="fakers center orange"><strong>Fakers</strong><br/><strong><span class="'+fkclass+'">'+result.spam+'</span></strong></div>');
        fake.appendTo(div1);

        var kred = $('<div class="kredsmall"><div class="influence"></div><div class="outreach"></div></div>'); 
        kred.appendTo(div1);

        div1.appendTo(div);

        var div2 = $('<div/>');

        div2.html('<span class="screenname">'+result.screenname+'</span><br/>'+result.location+'<br/><a href="'+result.url+'" target="_blank">Website</a><br/>Tweets: '+result.tweets+'<br/>Followers: '+result.followers+' <a href="http://twitter.com/'+result.screenname+'" class="tweetfollowers">View</a><br/>Friends: '+result.friends+'<br/>Days Active: '+result.daysactive); 
       
        var srv = new Server();
        srv.CallServer('GET','json','/API/GetKredScore','rf=json&gk='+gk+'&usr='+result.screenname,'Tweets_AddKredScore',kred);
        
        div2.appendTo(div);
        
        var div3 = $('<div/>');

        div3.text(result.description);
        
        div3.appendTo(div);
        
        var div4 = $('<div/>');

        //var following = (result.following==1)?'<a href="/SocialMedia/FollowTweeter/V/'+result.id+'/'+result.screenname+'" class="unfollow" title="Unfollow">t9</a>':'<a href="/SocialMedia/FollowTweeter/V/'+result.id+'/'+result.screenname+'" class="follow" title="Follow">t;</a>';

        //div4.html(following+'<a href="" data-tweetname="'+result.screenname+'" class="reply" title="Reply">y</a><a href="http://twitter.com/'+result.screenname+'" class="usertweettimeline" title="User Timeline">"</a><a href="#" class="senddm" rel="'+result.screenname+'" title="Direct Message">%</a>');
        
		div4.html('<a href="http://twitter.com/'+result.screenname+'" class="usertweettimeline" title="User Timeline">"</a>');
		
        div4.appendTo(div);
        
        div.appendTo(li);
        
        return li;
    }
	
    this.BuildDM = function(DM)
    {
        
    }
    
    this.BuildRetweet = function(retweet)
    {
        
    }
    
/*     this.AddKredScore = function(result,p2)
    {
        if (result.code == 201)
        {
            p2.html('<img src="/Pie/Crust/Template/img/Kred_Logo.png" /> '+result.data.influence+'/'+result.data.outreach);
        }
        else
        {
            p2.html('<img src="/Pie/Crust/Template/img/Kred_Logo.png" /> N/A');
        }
    } */
	
	this.AddKredScore = function(result,kred)
    {
        if (result.code == 201)
        {
            kred.children('.influence').html(result.data.influence);
            kred.children('.outreach').html(result.data.outreach);
        }
        else
        {
            kred.children('.influence').html('0');
            kred.children('.outreach').html('0');
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
            var ul = $('<ul class="twittertimeline"/>');
            
            $.each(result.data,function(i,t){
                var tweet = tw.BuildTweet(t);
                tweet.prependTo(ul);
            });
            
            pop.Content(ul,'popupscroll');
        }
        else
        {
            pop.AddMessage('No tweets found for this user', true);
        }
            
    }
    
}

function Format()
{
	this.Number = function(val)
	{
		
	    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

	}
}