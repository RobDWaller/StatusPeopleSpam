$(document).ready(function(){
    
    var twid = $('#twitterid').val();
    var twuser = $('#twitterhandle').val();
    
    var srv = new Server();
    var ln = new Lengths();
    var pop = new Popup();
    
    function Build()
    {
        if ($('#scoresholder').length)
        {
            $('#scoresholder').remove();
        }
        
        if ($('#shareform').length)
        {
            $('#shareform').remove();
        }
        
        var div = $('<div/>');
        div.attr('id','scoresholder');
        div.addClass('row');
        
        div.insertBefore('#SearchForm');
    }
    
    function Loader()
    {
        var loader = $('<div/>');
        loader.attr('id','loader');
        loader.html('<h1>Getting Faker Scores</h1>');
        loader.attr('class','row connect center');
        
        var img = $('<img/>');
        img.attr('src','/Pie/Crust/Template/img/287.gif');
        img.attr('id','imageloader');
        
        img.appendTo(loader);
        
        loader.appendTo('#scoresholder');
    }
    
    function Begin()
    {
        $('#GetScoresForms').remove();
        
        Build();
        Loader();
        
//        GetScores(twid,twuser,1);

        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+twuser,'Spam_ProcessSpamData',1);
    }
    
    $('#searchsubmit').bind('click',function(e){
        
        e.preventDefault();
        
        $(this).attr('disabled','disabled');
        
        var usersearch = $('#searchquery').val();
        
        var sl = ln.StringLength(usersearch);
        
        if (sl > 0)
        {
            Build();
            Loader();
            
            $('#handle').text(usersearch);

    //        GetScores(twid,usersearch,2);
            srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+usersearch,'Spam_ProcessSpamData',2);
        }
        else
        {
            pop.Loader();
            pop.AddMessage('Please enter a Twitter username to search for.',true);
            $(this).removeAttr('disabled');
        }
    });
    
    $('#resetscores').live('click',function(e){
        
        e.preventDefault();
        
        Build();
        Loader();
        
        $('#handle').text(twuser);
        $('#searchquery').val('');
        $('#searchquery').attr('placeholder','Twitter username...');
        
        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+twuser,'Spam_ProcessSpamData',1);
        
    });
    
    $('#popupclose').live('click',function(){
        
        pop.RemovePopup();
        
    });
    
    Begin();
    
});