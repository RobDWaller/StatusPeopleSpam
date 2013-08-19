$(document).ready(function(){
   
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
   
});