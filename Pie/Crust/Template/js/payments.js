$(document).ready(function(){
    
    var pay = new Payments();
    
    $('#Currency').bind('change',function(){
       
       var months = $('#Period').val();
       var currency = $(this).val();
       
       pay.RecalculateCart(currency,months);
       
    });
    
    $('#Period').bind('change',function(){
       
       var months = $(this).val();
       var currency = $('#Currency').val();
       
       pay.RecalculateCart(currency,months);
       
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
    
});