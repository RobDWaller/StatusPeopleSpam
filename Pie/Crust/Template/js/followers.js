$(document).ready(function(){
	
	var twid = $('#twitterid').val();
    var twuser = $('#twitterhandle').val();

    var srv = new Server();
    var pop = new Popup();
    var sc = new Scroll();
    var ln = new Lengths();

    function BuildChart()
    {
       	srv.CallServer('GET','json','/API/GetSpamScoresOverTime','rf=json&usr='+twid,'Charts_BuildChart',[{'id':'chart','type':'line','multi':true,'size':'large','xreverse':true,'backgroundcolor':'#fefefe','colors':['#FE1B2A', '#fe7d1d', '#2AFE1B']}]);
    }
	
	function GetStats()
	{
		srv.CallServer('GET','json','/API/GetCacheData','rf=json&usr='+twid,'Spam_ProcessCacheData');
	}
	
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
        
        srv.CallServer('POST','json','/API/PostDeleteFaker','rf=json&usr='+twid+'&twid='+id,'Spam_DeleteFaker',twid);        
    });

    $(document).on('click','.chart',function(){
        var pr = $(this).parent();
        var inp1 = pr.children('.ti');
        var id = inp1.val();
        var inp2 = pr.children('.sc');
        var nm = inp2.val();
        
        $('#charthandle').text(nm);
        
        srv.CallServer('GET','json','/API/GetSpamScoresOverTime','rf=json&usr='+id,'Charts_BuildChart',[{'id':'chart','type':'line','multi':true,'size':'large','xreverse':false,'backgroundcolor':'#fefefe','colors':['#FE1B2A', '#fe7d1d', '#2AFE1B']}]);
		srv.CallServer('GET','json','/API/GetCacheData','rf=json&usr='+id,'Spam_ProcessCacheData');
        
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

            srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+usersearch+'&srchs=3','Spam_ProcessSpamDataPopup',twid);
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

        srv.CallServer('POST','json','/API/PostAddFaker','rf=json&usr='+twid+'&srch='+usersearch+'&sp='+spam+'&pt='+potential+'&ch='+checks+'&fl='+followers,'Spam_AddFaker',twid);

        pop.RemovePopup();

        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+twuser,'Spam_ProcessSpamData',3);


    });
	
	BuildChart();
	GetStats();
	
});