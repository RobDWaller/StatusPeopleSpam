$(document).ready(function(){
	
	var srv = new Server();
	var pop = new Popup();
	var ln = new Lengths();
	var pop = new Popup();
	var ms = new Messages();
	
	$('#fakersitesubmit').bind('click',function(e){
		
		e.preventDefault();
		
		var url = $('#fakersite').val();
		
		var sl = ln.StringLength(url);
		
		if (sl>0)
		{	
			pop.TinyLoader();
		
			srv.CallServer('POST','json','/API/PostAddSite','rf=json&url='+encodeURIComponent(url),'Spam_ProcessUserAddSite');
		}
		else
		{
			ms.Build('alert',['Please enter a valid website URL.'],'.header');
		}
	});
	
});