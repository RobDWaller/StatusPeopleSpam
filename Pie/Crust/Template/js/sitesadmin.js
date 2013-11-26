$(document).ready(function(){
	
	var srv = new Server(); 
	
	function begin()
	{
		$.each($('#fakersuggestions li'),function(i,li){
			
			var url = $(this).children('fieldset').children('.siteurl').val();
			
			srv.CallServer('GET','json','/API/GetUrlDetails','rf=json&url='+encodeURIComponent(url),'Spam_ProcessSiteData',$(this));
		});
	}
	
	begin();
	
});