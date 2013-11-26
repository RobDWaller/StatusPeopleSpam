$(document).ready(function(){
	
	var twid = $('#twitterid').val();
	var twid2 = $('#twitterid2').val();
    var twuser = $('#twitterhandle').val();
	var type = $('#accounttype').val();

    var srv = new Server();
    var pop = new Popup();
    var sc = new Scroll();
    var ln = new Lengths();

    function BuildChart()
    {
       	srv.CallServer('GET','json','/API/GetSpamScoresOverTime','rf=json&usr='+encodeURIComponent(twid2),'Charts_BuildChart',[{'id':'chart','type':'line','multi':true,'size':'large','xreverse':true,'backgroundcolor':'#fefefe','colors':['#FE1B2A', '#fe7d1d', '#2AFE1B']}]);
    }
	
	function GetStats()
	{
		srv.CallServer('GET','json','/API/GetCacheData','rf=json&usr='+encodeURIComponent(twid2),'Spam_ProcessCacheData');
	}
	
	function CompList()
	{
		var c = $('.competitorlist tr').length;
		
		//alert(c);
		
		if (c > 3)
		{
			var k = 0;
			
			$.each($('.competitorlist tr'),function(i,t){
			
				if (k>2)
				{
					$(this).hide();	
				}
				
				k+=1;	
				
			});
			
			var tr = $('<tr><td colspan="7" class="center pointer blue" id="compmore" data-state="0">Show More</td></tr>');
			
			tr.appendTo('.competitorlist tbody');
		}
	}
	
	$(document).on('click','#compmore',function(e){
		
		e.preventDefault();
		
		var st = parseInt($(this).attr('data-state'));
		
		if (st==0)
		{
			$('.competitorlist tr').show();
			$(this).attr('data-state','1');
			$(this).text('Hide');
		}
		else
		{
			$(this).attr('data-state','0');
			$(this).text('Show More');
			var c = $('.competitorlist tr').length;
		
			//alert(c);
			
			if (c > 3)
			{
				var k = 1;
				
				$.each($('.competitorlist tr'),function(i,t){
				
					if (k>3&&k<c)
					{
						$(this).hide();	
					}
					
					k+=1;	
					
				});
			}
			
			var sc = new Scroll();
			sc.To('.competitorlist',0,50);
		}
		
	});
	
	$(document).on('click','.delete',function(){
        var pr = $(this).parent();
        var inp = pr.children('input');
        var id = inp.val();
        
        var mes = new Messages();
        mes.DeleteCheck(id);
    });
    
    $(document).on('click','#deleteno',function(){

        pop.RemovePopup();

    });

    $(document).on('click','#popupclose',function(){

        pop.RemovePopup();

    });

    $(document).on('click','#deleteyes',function(){

        var id = $('#deletedata').val();
        pop.RemovePopup();
        
        srv.CallServer('POST','json','/API/PostDeleteFaker','rf=json&usr='+encodeURIComponent(twid)+'&twid='+encodeURIComponent(id),'Spam_DeleteFaker',twid);        
    });

    $(document).on('click','.chart',function(){
        var pr = $(this).parent();
        var inp1 = pr.children('.ti');
        var id = inp1.val();
        var inp2 = pr.children('.sc');
        var nm = inp2.val();
        
        $('#charthandle').text(nm);
        
        srv.CallServer('GET','json','/API/GetSpamScoresOverTime','rf=json&usr='+encodeURIComponent(id),'Charts_BuildChart',[{'id':'chart','type':'line','multi':true,'size':'large','xreverse':true,'backgroundcolor':'#fefefe','colors':['#FE1B2A', '#fe7d1d', '#2AFE1B']}]);
		srv.CallServer('GET','json','/API/GetCacheData','rf=json&usr='+encodeURIComponent(id),'Spam_ProcessCacheData');
        
        sc.To('#charthandle',500,40);
        
/*         if (!$('#chartreset').length)
        {
            var span = $('<span id="chartreset"/>');
            span.text('Reset');
            span.insertAfter('#charttitle');
        } */
        
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
	
	$(document).on('click','#addfakerpopup',function(e){
       
        e.preventDefault();

        var usersearch = $('#friendsearchname').text();
        var spam = $('#spam').val();
        var potential = $('#potential').val();
        var checks = $('#checks').val();
        var followers = $('#followers').val();

        srv.CallServer('POST','json','/API/PostAddFaker','rf=json&usr='+encodeURIComponent(twid)+'&srch='+usersearch+'&sp='+spam+'&pt='+potential+'&ch='+checks+'&fl='+followers,'Spam_AddFaker',twid);

        pop.RemovePopup();

        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+encodeURIComponent(twid)+'&srch='+twuser,'Spam_ProcessSpamData',3);


    });
	
	$(document).on('click','#gotopremium',function(e){
		
		e.preventDefault();
		
		window.location = '/Payments/Subscriptions?type=2';
		
	});
	
	$(document).on('click','#rightinfoclose',function(e){
		
		e.preventDefault();
		
		pop.RightInfoClose();
		
	});
	
	$(document).on('click','.details',function(e){
        
        e.preventDefault();
        
        var srch = $(this).attr('data-sc');
		
        pop.Loader('Loading...');
       
        srv.CallServer('GET','json','/API/GetTwitterUserData','rf=json&usr='+encodeURIComponent(twid)+'&srch='+srch,'Spam_BuildUser','');
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
	
	if (type==1)
	{
		pop.BuildRightInfoBox();
		
		var p = $('<p><strong>Auto Block</strong></p><p>To Auto Block your Fake Followers and track up to 15 Friends upgrade to a Premium subscription.</p><form><fieldset><input type="button" id="gotopremium" value="Go Premium" /></fieldset></form>');
		
		pop.RightInfoContent(p);
	}
	
	CompList();
	BuildChart();
	GetStats();
	
});