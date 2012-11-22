$(document).ready(function(){
   
    var twid = $('#twitterid').val();
    var twuser = $('#twitterhandle').val();

    var srv = new Server();
    var pop = new Popup();
    var sc = new Scroll();
    var ln = new Lengths();

    function BuildChart()
    {
       srv.CallServer('GET','json','/API/GetSpamScoresOverTime','rf=json&usr='+twid,'Charts_BuildChart');
    }

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

    function Loader(message,where)
    {
        var loader = $('<div/>');
        loader.attr('id','loader');
//        loader.html('<h1>Getting Faker Scores</h1>');
        loader.html('<h1>'+message+'</h1>');
        loader.attr('class','row connect center');

        var img = $('<img/>');
        img.attr('src','/Pie/Crust/Template/img/287.gif');
        img.attr('id','imageloader');

        img.appendTo(loader);

//        loader.appendTo('#scoresholder');
        loader.appendTo(where);
    }

    function Begin()
    {
        $('#GetScoresForms').remove();

        Build();
        Loader('Getting Faker Scores','#scoresholder');

//        GetScores(twid,twuser,1);

        var ft = parseInt($('#firsttime').val());

        if (ft==1)
        {
            srv.CallServer('GET','json','/API/GetSpamRecords','rf=json&usr='+twid+'&srch='+twuser,'Spam_ProcessSpamDataFirstTime',twid);
        }
        else
        {
            srv.CallServer('GET','json','/API/GetCachedSpamScore','rf=json&usr='+twid,'Spam_ProcessCachedSpamData',1);
        }
    }

    $('#searchsubmit').bind('click',function(e){

        e.preventDefault();
        
        $(this).attr('disabled','disabled');

        var usersearch = $('#searchquery').val();

        var sl = ln.StringLength(usersearch);
        
        if (sl > 0)
        {

            Build();
            Loader('Getting Faker Scores','#scoresholder');

            srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+usersearch,'Spam_ProcessSpamDataAdvanced',twid);

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
        Loader('Getting Faker Scores','#scoresholder');

        $('#handle').text(twuser);
        $('#searchquery').val('');
        $('#searchquery').attr('placeholder','Twitter username...');

        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+twuser,'Spam_ProcessSpamData',1);

    });

    $('#addfaker').live('click',function(e){
       
        e.preventDefault();

        var usersearch = $('#searchquery').val();
        var spam = $('#spam').val();
        var potential = $('#potential').val();
        var checks = $('#checks').val();
        var followers = $('#followers').val();

        srv.CallServer('POST','json','/API/PostAddFaker','rf=json&usr='+twid+'&srch='+usersearch+'&sp='+spam+'&pt='+potential+'&ch='+checks+'&fl='+followers,'Spam_AddFaker',twid);

        Build();
        Loader('Getting Faker Scores','#scoresholder');

        $('#handle').text(twuser);
        $('#searchquery').val('');
        $('#searchquery').attr('placeholder','Twitter username...');

        srv.CallServer('GET','json','/API/GetSpamScores','rf=json&usr='+twid+'&srch='+twuser,'Spam_ProcessSpamData',1);


    });
    
    $('.delete').live('click',function(){
        var pr = $(this).parent();
        var inp = pr.children('input');
        var id = inp.val();
        
        var mes = new Messages();
        mes.DeleteCheck(id);
    });
    
    $('#deleteno').live('click',function(){

        pop.RemovePopup();

    });

    $('#popupclose').live('click',function(){

        pop.RemovePopup();

    });

    $('#deleteyes').live('click',function(){

        var id = $('#deletedata').val();
        pop.RemovePopup();
        
        srv.CallServer('POST','json','/API/PostDeleteFaker','rf=json&usr='+twid+'&twid='+id,'Spam_DeleteFaker',twid);        
    });

    $('.chart').live('click',function(){
        var pr = $(this).parent();
        var inp1 = pr.children('.ti');
        var id = inp1.val();
        var inp2 = pr.children('.sc');
        var nm = inp2.val();
        
        $('#charthandle').text(nm);
        
        srv.CallServer('GET','json','/API/GetSpamScoresOverTime','rf=json&usr='+id,'Charts_BuildChart');
        
        sc.To('#charthandle',500,10);
        
        if (!$('#chartreset').length)
        {
            var span = $('<span id="chartreset"/>');
            span.text('Reset');
            span.insertAfter('#charttitle');
        }
        
    });

    $('.details').live('click',function(e){
        
        e.preventDefault();
        
        var li = $(this).parent().parent();
        var inp = li.children('.sc');
        var srch = inp.val();
        
        pop.Loader('Loading...');
       
        srv.CallServer('GET','json','/API/GetTwitterUserData','rf=json&usr='+twid+'&srch='+srch,'Spam_BuildUser','');
    });
    
    $('.block').live('click',function(e){
        
        e.preventDefault();
        
        var li = $(this).parent().parent();
        var inp = li.children('.ti');
        var srch = inp.val();
        
        srv.CallServer('POST','json','/API/PostBlockSpam','rf=json&usr='+twid+'&twid='+srch,'Spam_BlockUser',twid);
    });
    
    $('.notspam').live('click',function(e){
        
        e.preventDefault();
        
        var li = $(this).parent().parent();
        var inp = li.children('.ti');
        var srch = inp.val();
        
        srv.CallServer('POST','json','/API/PostNotSpam','rf=json&usr='+twid+'&twid='+srch,'Spam_NotSpam',twid);
    });
    
    $('.tweetfollowers').live('click',function(e){
       
        e.preventDefault();

        var content = $(this).attr("href");
        var sp = content.split("//");
        var sn = sp[1].split("/");

        pop.Loader('Loading...');

        srv.CallServer('GET','json','/API/GetFollowerData','rf=json&usr='+twid+'&ct=10&nm='+sn[1],'Tweets_Followers');
        
    });
    
    $(".usertweettimeline").live("click", function (e){
       
       e.preventDefault();
       
       pop.Loader('Loading...');
       pop.Content('');
       
       var url = $(this).attr("href");
       var sn = url.split("/");
       
       srv.CallServer('GET','json','/API/GetUserTwitterTimeline','rf=json&usr='+twid+'&srch='+sn[3]+'&cnt=10','Tweets_BuildUserTimeline');
       
    });
    
    $("#chartreset").live('click',function(){
       
       $('#charthandle').text(twuser);
        
        srv.CallServer('GET','json','/API/GetSpamScoresOverTime','rf=json&usr='+twid,'Charts_BuildChart');
        
        sc.To('#charthandle',500,10);
        
        $(this).fadeOut(1000,function(){
           $(this).remove(); 
        });
       
    });
    
    $('#checkfakes').live('click',function(){
       
       if ($('#checkform').length)
       {
           $('#checkform').remove();
       }
       
       Loader('Checking For New Fake Followers','#spammers');
       
       srv.CallServer('GET','json','/API/GetUpdateFakersList','rf=json&usr='+twid+'&srch='+twuser,'Spam_UpdateFakersList',twid);
       
    });

    Begin();

    BuildChart();
    
});