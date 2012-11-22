$(document).ready(function(){
   
   $('#failureclose').live('click',function(){
      
      $('#failuremessage').fadeOut("slow",function(){$(this).remove()});
      
   });
   
   $('#successclose').live('click',function(){
      
      $('#successmessage').fadeOut("slow",function(){$(this).remove()});
      
   });
   
   $('#infoclose').live('click',function(){
      
      $('#infomessage').fadeOut("slow",function(){$(this).remove()});
      
   });
   
   $('#alertclose').live('click',function(){
      
      $('#alertmessage').fadeOut("slow",function(){$(this).remove()});
      
   });
   
});