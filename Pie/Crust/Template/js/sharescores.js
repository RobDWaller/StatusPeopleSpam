$(document).ready(function(){
    
    var srv = new Server();
    var spm = new Spam();    
        
    $(document).on('click','#sharescores',function(e){
        
        e.preventDefault();
        
        spm.Sharing();
        
        var spamscore = $('#spamscore').val();
        var twid = $('#twitterid').val();
        
        var tweet = encodeURIComponent(spamscore+'% of my followers are fake. How many fake followers do you have..? http://fakers.statuspeople.com @StatusPeople #FollowerSpam');
        
//        ShareScores(twid,tweet);
        
        srv.CallServer('POST','json','/API/PostTweet','rf=json&usr='+encodeURIComponent(twid)+'&txt='+tweet,'Spam_ProcessSpamScoreShare','');
        
    });
    
});