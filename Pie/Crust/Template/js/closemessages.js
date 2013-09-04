$(document).ready(function(){
   
	var pop = new Popup();
	
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
	
	infobox();
   
});