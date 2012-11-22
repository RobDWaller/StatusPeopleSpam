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
                button.val('Share Your Scores');
            }
            else if (source==2)
            {
                button.attr('id','resetscores');
                button.val('Display Your Scores');    
            }
            
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
            button.val('Share Your Scores');
            
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
                mes.Build('alert',ma,'.columnholder');
            }
            
            input.appendTo('#shareform');
        }
        else
        {
            ma[0] = 'Could not find user details on Twitter please try your search again.';
            mes.Build('failure',ma,'.columnholder');
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
            mes.Build('success',ma,'.columnholder');
        }
        else
        {
            ma[0]='Failed to add user to Fakers List, please try again';
            mes.Build('failure',ma,'.columnholder');
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
            mes.Build('success',ma,'.columnholder');
        }
        else
        {
            ma[0]='Failed to remove user from Fakers List, please try again';
            mes.Build('failure',ma,'.columnholder');
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
                tr.html('<td><img src="'+r.avatar+'" width="48px" height="48px" /></td><td><span>'+r.screen_name+'</span></td><td><span class="red">Fake: '+fake+'%</span></td><td><span class="orange">Inactive: '+inactive+'%</span></td><td><span class="green">Good: '+good+'%</span></td><td><input type="hidden" value="'+r.twitterid+'" class="ti"/><input type="hidden" value="'+r.screen_name+'" class="sc"/><span class="chart" title="View on chart"><img src="/Pie/Crust/Template/img/Reports.png"/></span></td><td><input type="hidden" value="'+r.twitterid+'"/><span class="delete" title="Remove">X</span></td>');
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
            mes.Build('success',ma,'.columnholder');
        }
        else
        {
            ma[0]='Failed to block user, please try again.';
            mes.Build('failure',ma,'.columnholder');
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
            mes.Build('success',ma,'.columnholder');
        }
        else
        {
            ma[0]='Failed to mark user as not spam, please try again.';
            mes.Build('failure',ma,'.columnholder');
        }
        
        srv.CallServer('GET','json','/API/GetSpamList','rf=json&usr='+user,'Spam_BuildFakersList','');
        
    }
}