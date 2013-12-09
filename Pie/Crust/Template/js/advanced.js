$(document).ready(function(){
   
    var twid = $('#twitterid').val();
    var twuser = $('#twitterhandle').val();
	var type = $('#accounttype').val();

    var srv = new Server();
    var pop = new Popup();
    var sc = new Scroll();
    var ln = new Lengths();

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

    function Loader(message,where)
    {
        var loader = $('<div/>');
        loader.attr('id','loader');
//        loader.html('<h1>Getting Faker Scores</h1>');
        loader.html('<h1>'+message+'</h1>');
        loader.attr('class','row connect center');

        var img = $('<img/>');
        img.attr('src','/Pie/Crust/Template/img/287.gif');
        img.attr('id','imageloader');

        img.appendTo(loader);

//        loader.appendTo('#scoresholder');
        loader.appendTo(where);
    }

    function Begin()
    {
        $('#GetScoresForms').remove();

        Build();
        Loader('Getting Faker Scores','#scoresholder');

//        GetScores(twid,twuser,1);

        var ft = parseInt($('#firsttime').val());

        if (ft==1)
        {
            srv.CallServer('GET','json','/API/GetSpamRecords','rf=json&usr='+encodeURIComponent(twid)+'&srch='+twuser,'Spam_ProcessSpamDataFirstTime',twid);
        }
        else
        {
            srv.CallServer('GET','json','/API/GetCachedSpamScore','rf=json&usr='+encodeURIComponent(twid),'Spam_ProcessCachedSpamData',1);
        }
    }

    $('#searchsubmit').bind('click',function(e){

        e.preventDefault();
        
        $(this).attr('disabled','disabled');

        var usersearch = $('#searchquery').val();

        var sl = ln.StringLength(usersearch);
        
        if (sl > 0)
        {

            Build();
            Loader('Getting Faker Scores','#scoresholder');
			
			$('#handle').text(usersearch);

            srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+encodeURIComponent(twid)+'&srch='+usersearch+'&srchs=3','Spam_ProcessSpamDataAdvanced',twid);

        }
        else
        {
            pop.Loader();
            pop.AddMessage('Please enter a Twitter username to search for.',true);
            $(this).removeAttr('disabled');
        }

    });

    $(document).on('click','#resetscores',function(e){

        e.preventDefault();

        Build();
        Loader('Getting Faker Scores','#scoresholder');

        $('#handle').text(twuser);
        $('#searchquery').val('');
        $('#searchquery').attr('placeholder','Twitter username...');

        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+encodeURIComponent(twid)+'&srch='+twuser,'Spam_ProcessSpamData',3);

    });

    $(document).on('click','#addfaker',function(e){
       
        e.preventDefault();

        var usersearch = $('#searchquery').val();
        var spam = $('#spam').val();
        var potential = $('#potential').val();
        var checks = $('#checks').val();
        var followers = $('#followers').val();

        srv.CallServer('POST','json','/API/PostAddFaker','rf=json&usr='+encodeURIComponent(twid)+'&srch='+usersearch+'&sp='+spam+'&pt='+potential+'&ch='+checks+'&fl='+followers,'Spam_AddFaker',twid);

        Build();
        Loader('Getting Faker Scores','#scoresholder');

        $('#handle').text(twuser);
        $('#searchquery').val('');
        $('#searchquery').attr('placeholder','Twitter username...');

        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+encodeURIComponent(twid)+'&srch='+twuser,'Spam_ProcessSpamData',3);


    });
	
	$(document).on('click','#addfakerpopup',function(e){
       
        e.preventDefault();

        var usersearch = $('#friendsearchname').text();
        var spam = $('#spam').val();
        var potential = $('#potential').val();
        var checks = $('#checks').val();
        var followers = $('#followers').val();

        srv.CallServer('POST','json','/API/PostAddFaker','rf=json&usr='+encodeURIComponent(twid)+'&srch='+usersearch+'&sp='+spam+'&pt='+potential+'&ch='+checks+'&fl='+followers,'Spam_AddFaker',twid);

        pop.RemovePopup();


    });
	
    $(document).on('click','.details',function(e){
        
        e.preventDefault();
        
        var li = $(this).parent().parent();
        var inp = li.children('.sc');
        var srch = inp.val();
        
        pop.Loader('Loading...');
       
        srv.CallServer('GET','json','/API/GetTwitterUserData','rf=json&usr='+encodeURIComponent(twid)+'&srch='+srch,'Spam_BuildUser','');
    });
    
    $(document).on('click','.block',function(e){
        
        e.preventDefault();
        
        var li = $(this).parent().parent();
        var inp = li.children('.ti');
        var srch = inp.val();
        
        srv.CallServer('POST','json','/API/PostBlockSpam','rf=json&usr='+encodeURIComponent(twid)+'&twid='+srch,'Spam_BlockUser',twid);
    });
    
	$(document).on('click','.unblock',function(e){
        
        e.preventDefault();
        
        var li = $(this).parent().parent();
        var inp = li.children('.ti');
        var srch = inp.val();
        
        srv.CallServer('POST','json','/API/PostUnBlockSpam','rf=json&usr='+encodeURIComponent(twid)+'&twid='+srch,'Spam_UnBlockUser',twid);
    });
	
    $(document).on('click','.notspam',function(e){
        
        e.preventDefault();
        
        var li = $(this).parent().parent();
        var inp = li.children('.ti');
        var srch = inp.val();
        
        srv.CallServer('POST','json','/API/PostNotSpam','rf=json&usr='+encodeURIComponent(twid)+'&twid='+srch,'Spam_NotSpam',twid);
    });
    
    $(document).on('click','.tweetfollowers',function(e){
       
        e.preventDefault();

        var content = $(this).attr("href");
        var sp = content.split("//");
        var sn = sp[1].split("/");

        pop.Loader('Loading...');

        srv.CallServer('GET','json','/API/GetFollowerData','rf=json&usr='+encodeURIComponent(twid)+'&ct=10&nm='+sn[1],'Tweets_Followers');
        
    });
    
    $(document).on("click",".usertweettimeline", function (e){
       
       e.preventDefault();
       
       pop.Loader('Loading...');
       pop.Content('');
       
       var url = $(this).attr("href");
       var sn = url.split("/");
       
       srv.CallServer('GET','json','/API/GetUserTwitterTimeline','rf=json&usr='+encodeURIComponent(twid)+'&srch='+sn[3]+'&cnt=10','Tweets_BuildUserTimeline');
       
    });
    
    $(document).on('click',"#chartreset",function(){
       
       $('#charthandle').text(twuser);
        
        srv.CallServer('GET','json','/API/GetSpamScoresOverTime','rf=json&usr='+encodeURIComponent(twid),'Charts_BuildChart');
        
        sc.To('#charthandle',500,10);
        
        $(this).fadeOut(1000,function(){
           $(this).remove(); 
        });
       
    });
    
    $(document).on('click','#checkfakes',function(){
       
/*        if ($('#checkform').length)
       {
           $('#checkform').remove();
       } */
       
       //Loader('Checking For New Fake Followers','#spammers');
       
		pop.TinyLoader();	
		
       srv.CallServer('GET','json','/API/GetUpdateFakersList','rf=json&usr='+encodeURIComponent(twid)+'&srch='+twuser,'Spam_UpdateFakersList',twid);
       
    });

	$(document).on('click','#popupclose',function(){

        pop.RemovePopup();

    });

	$(document).on('click','#friendsearch',function(e){
		
		e.preventDefault();
		
		pop.Loader();
		
		var form = $('<p>Search for a friend\'s faker score</p><form><fieldset><input type="text" id="friendsearchquery" /></fieldset><fieldset><input type="submit" value="Search" id="searchforfriend" /></fieldset></form>');
		
		pop.Content(form);
	});
	
	$(document).on('click','#searchforfriend',function(e){

        e.preventDefault();
        
        var usersearch = $('#friendsearchquery').val();

        var sl = ln.StringLength(usersearch);
        
        if (sl > 0)
        {
			pop.TinyLoader();

            srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+encodeURIComponent(twid)+'&srch='+usersearch+'&srchs=3','Spam_ProcessSpamDataPopup',twid);
		}
        else
        {
            pop.AddMessage('Please enter a Twitter username to search for',true);
        }

    });
	
	$('#searchblocked').bind('click',function(){
		
		pop.BuildPopup();
		pop.Content('<p>Search Your Blocked Users<p><form><fieldset><input type="text" id="searchforblocked" /></fieldset></form><div id="blocksearchdata"></div>');
		
	});
	
	$(document).on('keyup','#searchforblocked',function(){
		
		var srch = $(this).val();
		
		srv.CallServer('POST','json','/API/PostBlockedSearch','rf=json&usr='+encodeURIComponent(twid)+'&srch='+srch,'Spam_ProcessFakerFind');
		
	});
	
	$(document).on('mouseup','#searchforblocked',function(){
		
		var srch = $(this).val();
		
		srv.CallServer('POST','json','/API/PostBlockedSearch','rf=json&usr='+encodeURIComponent(twid)+'&srch='+srch,'Spam_ProcessFakerFind');
		
	});
	
	$(document).on('click','#autoon',function(e){
		
		e.preventDefault();
		
		pop.BuildPopup();
		
		var div = $('<div><p>If you turn on auto-blocking it will cause the Fakers App to begin blocking your fake followers as we find '+
						'them. You can search and unblock any follower at any time if you think we have got it wrong.</p>'+ 
					'<p>In addition if you have a lot of fake followers &mdash; tens of thousands &mdash; it may take '+
					'a number of days or even weeks before you notice a significant improvement in your scores. Your scores may also fluctuate '+ 
					'during this period. <strong>Please confirm you wish to turn on auto-blocking</strong></p>'+
					'<form><fieldset><input id="confirmautoon" value="Confirm" type="button"/></fieldset></form></div>');
		
		pop.Content(div);
		
	});
	
	$(document).on('click','#autooff',function(e){
		
		e.preventDefault();
		
		pop.TinyLoader();
		
		srv.CallServer('POST','json','/API/PostChangeAutoRemoveStatus','rf=json&usr='+encodeURIComponent(twid),'Spam_AutoBlockUpdate');
		
	});
	
	$(document).on('click','#confirmautoon',function(e){
		
		e.preventDefault();
		
		pop.TinyLoader();
		
		srv.CallServer('POST','json','/API/PostChangeAutoRemoveStatus','rf=json&usr='+encodeURIComponent(twid),'Spam_AutoBlockUpdate');
		
	});
	
	$(document).on('click','#gotopremium',function(e){
		
		e.preventDefault();
		
		window.location = '/Payments/Subscriptions?type=2';
		
	});
	
	$(document).on('click','#rightinfoclose',function(e){
		
		e.preventDefault();
		
		pop.RightInfoClose();
		
	});
	
	if (type==1)
	{
		pop.BuildRightInfoBox();
		
		var p = $('<p><strong>Auto Block</strong></p><p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p><form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');
		
		pop.RightInfoContent(p);
	}
	
    Begin();

});