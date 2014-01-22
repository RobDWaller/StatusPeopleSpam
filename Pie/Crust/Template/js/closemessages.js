$(document).ready(function(){
   
	var twid = $('#twitterid').val();
    var twuser = $('#twitterhandle').val();
	var type = $('#accounttype').val();
	
	var pop = new Popup();
	var srv = new Server();
	var sc = new Scroll();
    var ln = new Lengths();
	
	function infobox()
	{
		pop.InfoBox();
	}
	
   $(document).on('click','#failureclose',function(){
      
      $('#failuremessage').fadeOut("slow",function(){$(this).remove();});
      
   });
   
   $(document).on('click','#successclose',function(){
      
      $('#successmessage').fadeOut("slow",function(){$(this).remove();});
      
   });
   
   $(document).on('click','#infoclose',function(){
      
      $('#infomessage').fadeOut("slow",function(){$(this).remove();});
      
   });
   
   $(document).on('click','#alertclose',function(){
      
      $('#alertmessage').fadeOut("slow",function(){$(this).remove();});
      
   });
	
	$(document).on('mouseover','.icon',function(e){
		
		if ($(window).width()>640)
		{
			var message = $(this).data('tip');
			
			if ($(this).attr('id')=='toolbarbut')
			{
				if ($('#toolbarform').length)
				{
					message = 'Close Post';
				}
			}
			
			var div = $('<div class="iconmessage" style="z-index:2001; position:absolute; top:'+(e.pageY+15)+'px; left:'+(e.pageX-30)+'px; font-weight:bold; color:white; padding:5px; border-radius:5px;">'+message+'</div>');
			div.appendTo('body');
		}
		
	});
	
	$(document).on('mouseout','.icon',function(){
		
		$('.iconmessage').remove();
		
	});
	
	$(document).on('click','.icon',function(){
		
		$('.iconmessage').remove();
		
	});
	
	$(document).on('click','#webinarbut',function(e){
		
		e.preventDefault();
		
		window.location = $(this).attr('data-link');
		
	});
	
	$(document).on('click','#rightinfoclose',function(e){
		
		e.preventDefault();
		
		pop.RightInfoClose();
		
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
	
	$('#account').bind('change',function(){
		
		$('#changeaccountform').submit();
		
	});
	
	infobox();
   
});