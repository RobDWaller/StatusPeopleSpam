$(document).ready(function(){
    
    var twid = $('#twitterid').val();
    var twuser = $('#twitterhandle').val();
    
    var srv = new Server();
    var ln = new Lengths();
    var pop = new Popup();
	var ms = new Messages();
    
    function Build()
    {
        if ($('#scoresholder').length)
        {
            $('#scoresholder').remove();
        }
        
        if ($('#shareform').length)
        {
            $('#shareform').remove();
        }
        
        var div = $('<div/>');
        div.attr('id','scoresholder');
        div.addClass('row');
        
        div.insertBefore('#SearchForm');
    }
    
    function Loader()
    {
        var loader = $('<div/>');
        loader.attr('id','loader');
        loader.html('<h1>Getting Faker Scores</h1>');
        loader.attr('class','row connect center');
        
        var img = $('<img/>');
        img.attr('src','/Pie/Crust/Template/img/287.gif');
        img.attr('id','imageloader');
        
        img.appendTo(loader);
        
        loader.appendTo('#scoresholder');
    }
    
    function Begin()
    {
        $('#GetScoresForms').remove();
        
        Build();
        Loader();
        
//        GetScores(twid,twuser,1);
		var srchs = parseInt($.cookie('searches'));
        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+twuser+'&srchs='+srchs,'Spam_ProcessSpamData',1);
		srv.CallServer('GET','json','/API/GetUserDetailsCount','rf=json&usr='+twid,'Spam_ProcessUserCheck');
    }
    
    $('#searchsubmit').bind('click',function(e){
        
        e.preventDefault();
        
        $(this).attr('disabled','disabled');
        
        var usersearch = $('#searchquery').val();
        
        var sl = ln.StringLength(usersearch);
        var srchs = parseInt($.cookie('searches'));
		
		if (srchs>0)
		{
			if (sl > 0)
			{
				Build();
				Loader();
				
				$('#handle').text(usersearch);
	
		//        GetScores(twid,usersearch,2);
				srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+usersearch+'&srchs='+srchs,'Spam_ProcessSpamData',2);
			}
			else
			{
				ms.Build('alert',['Please enter a Twitter username to search for.'],'.header');
				$(this).removeAttr('disabled');
			}
		}
		else
		{
			ms.Build('alert',['You have no more friend searches left, please purchase a <a href="/Payments/Subscriptions">subscription</a> for unlimited searches.'],'.header');
			$(this).removeAttr('disabled');
		}
    });
    
    $(document).on('click','#resetscores',function(e){
        
        e.preventDefault();
        
        Build();
        Loader();
        
        $('#handle').text(twuser);
        $('#searchquery').val('');
        $('#searchquery').attr('placeholder','Twitter username...');
        
        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+twuser,'Spam_ProcessSpamData',1);
        
    });
    
    $(document).on('click','#popupclose',function(){
        
        pop.RemovePopup();
        
    });
    
	$(document).on('click','#freesearches',function(e){
		
		e.preventDefault();
		
		pop.BuildPopup();
		
		var form = $('<p>Fill in your details to get 5 Free Searches.</p>'+
					 '<form>'+
					 '<fieldset><label>Email:</label><input type="text" id="F5email"/></fieldset>'+
					 '<fieldset><label>Title:</label><select id="F5title">'+
					 '<option value="Mr">Mr</option>'+
					 '<option value="Mrs">Mrs</option>'+
					 '<option value="Miss">Miss</option>'+
					 '<option value="Ms">Ms</option>'+
					 '<option value="Dr">Dr</option>'+
					 '</select></fieldset>'+
					 '<fieldset><label>First Name:</label><input type="text" id="F5fname"/></fieldset>'+
					 '<fieldset><label>Last Name:</label><input type="text" id="F5lname"/></fieldset>'+
					 '<fieldset><input type="submit" id="F5submit"/></fieldset>'+
					 '</form>');
		
		pop.Content(form);
		
	});
	
	$(document).on('click','#F5submit',function(e){
		
		e.preventDefault();
		
		var email = $('#F5email').val();
		var title = $('#F5title').val();
		var fname = $('#F5fname').val();
		var lname = $('#F5lname').val();
		
		pop.TinyLoader();
		
		srv.CallServer('POST','json','/API/PostAddUserDetails','rf=json&usr='+twid+'&em='+email+'&tt='+title+'&fn='+fname+'&ln='+lname,'Spam_ProcessUserAddDetails',1);
		
	});
	
	$(document).on('click','#rightinfoclose',function(e){
		
		e.preventDefault();
		
		pop.RightInfoClose();
		
	});
	
	$(document).on('click','.details',function(e){
        
        e.preventDefault();
        
        var srch = $(this).attr('data-sc');
        
        pop.Loader('Loading...');
       
        srv.CallServer('GET','json','/API/GetTwitterUserData','rf=json&usr='+twid+'&srch='+srch,'Spam_BuildUser','');
    });
	
	$(document).on('click','.tweetfollowers',function(e){
       
        e.preventDefault();

        var content = $(this).attr("href");
        var sp = content.split("//");
        var sn = sp[1].split("/");

        pop.Loader('Loading...');

        srv.CallServer('GET','json','/API/GetFollowerData','rf=json&usr='+twid+'&ct=10&nm='+sn[1],'Tweets_Followers');
        
    });
    
    $(document).on("click",".usertweettimeline", function (e){
       
       e.preventDefault();
       
       pop.Loader('Loading...');
       pop.Content('');
       
       var url = $(this).attr("href");
       var sn = url.split("/");
       
       srv.CallServer('GET','json','/API/GetUserTwitterTimeline','rf=json&usr='+twid+'&srch='+sn[3]+'&cnt=10','Tweets_BuildUserTimeline');
       
    });
	
    Begin();
    
});