$(document).ready(function(){
    
    var pay = new Payments();
	var pop = new Popup();
	var subtype = $('#subtype').val();
    
    $('#Currency').bind('change',function(){
       
       var months = $('#Period').val();
       var currency = $(this).val();
		var type = $('#Type').val();
       
       pay.RecalculateCart(currency,months,type);
       
    });
    
    $('#Period').bind('change',function(){
       
       var months = $(this).val();
       var currency = $('#Currency').val();
       var type = $('#Type').val();
		
       pay.RecalculateCart(currency,months,type);
       
    });
	
	$('#Type').bind('change',function(){
		
		var months = $('#Period').val();
       	var currency = $('#Currency').val();
		var type = $(this).val();
       
       	pay.RecalculateCart(currency,months,type);
		
	});
    
    $('#METHOD').bind('click',function(){
       
       if ($('#tc').is(':checked'))
       {
           return true;
       }
       else
       {
           if (!$('#termswarning').length)
           {
               var p = $('<small id="termswarning" class="red"/>');
               p.text('Please agree to the Terms and Conditions.');
               p.insertBefore('#termstext');
           }
           
           return false;
       }
       
    });
	
	$(document).on('click','#rightinfoclose',function(e){
		
		e.preventDefault();
		
		pop.RightInfoClose();
		
	});
	
	// $(document).on('click','#gopremium',function(e){
		
		// e.preventDefault();
		
		// var months = $('#Period').val();
       	// var currency = $('#Currency').val();
		// var type = 2;
		
		// $('#Type').val(2);
       
       	// pay.RecalculateCart(currency,months,type);
		
	// });
	
	if (subtype == 2)
	{
		var months = $('#Period').val();
       	var currency = $('#Currency').val();
		var type = 2;
		
		$('#Type').val(2);
       
       	pay.RecalculateCart(currency,months,type);
	}
	
	pop.BuildRightInfoBox();
	pop.RightInfoContent($('<p><strong>Auto Block</strong></p><p>Go Premium to turn on Auto Faker Blocking and track up to 15 Friends.</p><form method="get" action="/Payments/Subscriptions?type=2"><fieldset><input type="button" id="gopremium" value="Go Premium"/></fieldset></form>'));
    
});