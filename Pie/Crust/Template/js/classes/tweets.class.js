function Tweets()
{
    
    this.BuildTweet = function(tweet)
    {
        var item = $('<li/>');
                
        var img = $('<img/>');
        img.attr('src',tweet.avatar);
        img.attr('height','48px');
        img.attr('width','48px');

        img.appendTo(item);

        var p1 = $('<p/>');
        var tw = this.HashAtLink(tweet.tweet);

        p1.append('<strong>'+tweet.name+'</strong> ');

        p1.append(tw);

        p1.appendTo(item);

        var p2 = $('<small/>');
        p2.addClass('orange');
        p2.html('Source: '+ tweet.source +' | Date: '+tweet.date);

        p2.appendTo(item);
        
        return item;
    }
    
    this.HashAtLink = function(tweet)
    {
        var rg1 = new RegExp('https?:\\/\\/([a-zA-Z0-9\\/\\_\\-\\.\\(\\)\\&\\?\\=]*)','g');
        var rg2 = new RegExp('^@([a-zA-Z0-9_-]{1,})','g');
        var rg3 = new RegExp('\\s@([a-zA-Z0-9_-]{1,})','g');
        var rg4 = new RegExp('^#([a-zA-Z0-9_-]{1,})','g');
        var rg5 = new RegExp('\\s#([a-zA-Z0-9_-]{1,})','g');
        
        var tw = tweet.replace(rg1,'<a href="http://$1" target="_blank" class="createdlink">$1</a>');
        tw = tw.replace(rg2,'<a href="http://twitter.com/$1" class="tweetuser" target="_blank">@$1</a>');
        tw = tw.replace(rg3,' <a href="http://twitter.com/$1" class="tweetuser" target="_blank">@$1</a>');
        tw = tw.replace(rg4,' <a href="http://twitter.com/search?q=%23$1" class="tweethash" target="_blank">#$1</a>');
        tw = tw.replace(rg5,' <a href="http://twitter.com/search?q=%23$1" class="tweethash" target="_blank">#$1</a>');
        
        
        return tw;
    }
    
    this.GetAtUsers = function(txt)
    {
        var rg1 = new RegExp('\\s@([a-zA-Z0-9_-]{1,})','g');
        
        var mtchs = txt.match(rg1);
        
        return mtchs;
    }
    
    this.CreateRetweet = function(txt)
    {
        var rg1 = new RegExp('^([a-zA-Z0-9_-]{1,})\\s');
        var rg2 = new RegExp('([a-zA-Z0-9]{1,}\\.[a-zA-Z0-9]{1,}\\/[a-zA-Z0-9]{1,})','g');
        
        var mtch = txt.match(rg1);
        
        var replace = 'RT @'+mtch[1]+': ';
        
        var newtxt = txt.replace(rg1,replace);
        newtxt = newtxt.replace(rg2,'http://$1');
        
        return newtxt;
    }
    
    this.BuildUser = function(result)
    {
        var li = $('<li/>');
        
        var div = $('<div/>');

        var img = $('<img/>');
        img.attr('width','48');
        img.attr('height','48');
        img.attr('src',result.image);
        img.appendTo(div);

        var p1 = $('<p/>'); 
        p1.html(result.screenname+'<br/>'+result.location+'<br/><a href="'+result.url+'" target="_blank">'+result.url+'</a><br/>Tweets: '+result.tweets+'<br/>Followers: '+result.followers+' <a href="http://twitter.com/'+result.screenname+'" class="tweetfollowers">View</a><br/>Friends: '+result.friends+'<br/>Days Active: '+result.daysactive);
        p1.appendTo(div);

        var p2 = $('<p/>'); 
        p2.attr('class','kred');
        p2.appendTo(div);

        var srv = new Server();
        srv.CallServer('GET','json','/API/GetKredScore','rf=json&usr='+result.screenname,'Tweets_AddKredScore',p2);
        
        var p3 = $('<p/>'); 
        p3.text(result.description);
        p3.appendTo(div);

//        var following = (result.following==1)?'Following':'<a href="/SocialMedia/FollowTweeter/V/'+result.id+'/'+result.screenname+'">Follow</a>';

        var p4 = $('<p/>');
        p4.html('<a href="http://twitter.com/'+result.screenname+'" class="usertweettimeline">Timeline</a>');
        p4.appendTo(div);
        
        div.appendTo(li);
        
        return li;
    }
    
    this.BuildDM = function(DM)
    {
        
    }
    
    this.BuildRetweet = function(retweet)
    {
        
    }
    
    this.AddKredScore = function(result,p2)
    {
        if (result.code == 201)
        {
            p2.html('<img src="/Pie/Crust/Template/img/Kred_Logo.png" /> '+result.data.influence+'/'+result.data.outreach);
        }
        else
        {
            p2.html('<img src="/Pie/Crust/Template/img/Kred_Logo.png" /> N/A');
        }
    }
    
    this.Timeline = function(result,id)
    {
        var tw = new Tweets();
        var hub = new Hub();
        var listid = '#twitter'+id;
        var holderid = '#timelineholder'+id;
        
        if (result.code == 201)
        {
            var c = 0;
            
            if ($(listid).length)
            {
                $.each(result.data,function(i,twt){
                    
                    if (!c)
                    {
                        $('#sinceid'+id).val(twt.id);
                    }
                    
                    var tweet = tw.BuildTweet(twt);
                    tweet.prependTo(listid);
                    c=c+1;
                    
                });
            }
            else
            {
                var ul = $('<ul/>');
                ul.attr('id','twitter'+id);
                ul.addClass('twittertimeline');
                
                $.each(result.data,function(i,twt){
                    
                    if (!c)
                    {
                        $('#sinceid'+id).val(twt.id);
                    }
                    
                    var tweet = tw.BuildTweet(twt);
                    tweet.appendTo(ul);
                    c=c+1;
                    
                });
                
                ul.appendTo(holderid);
            }
        }
        else
        {
            if ($(listid).length)
            {
                //Do Nothing
            }
            else
            {
                var pop = new Popup();
                pop.Loader();
                pop.AddMessage('No data returned',true);
            }
        }
        
        if ($('#viewnewtweets').length)
        {
            $('#viewnewtweets').remove();
        }
        
        hub.Loader('');
    }

    this.Followers = function(result)
    {
        var pop = new Popup();
        var tw = new Tweets();
        
        if (result.code == 201)
        {
            var ul = $('<ul/>');
            ul.addClass('twitterusers');
            
            $.each(result.data,function(i,u){
               
               var usr = tw.BuildUser(u);
               usr.appendTo(ul);
               
            });
            
            pop.Content(ul,'popupscroll');
        }
        else
        {
            pop.AddMessage('No follower data returned at this time.',true);
        }
            
        pop.RemoveLoader();
    }

    this.NewCount = function(result)
    {

        if (result.code == 201)
        {
            var hub = new Hub();
            var c = 0;
            
            $.each(result.data,function(i,r)
            {
                c += 1;
            });

            if ($('#viewnewtweets').length)
            {
                $('#viewnewtweets').text('You have '+c+' new Tweets to view.');
            }
            else
            {
                var div = $('<div/>');
                div.attr('id','viewnewtweets');
                div.text('You have '+c+' new Tweets to view.');
                div.insertBefore('#timelineholderhome')
            }
        }
        
        hub.Loader('');
    }
    
    this.BuildUserTimeline = function(result)
    {
        var tw = new Tweets();
        var pop = new Popup();
        
        pop.RemoveLoader();
        pop.RemoveMessage();
        
        if (result.code == 201)
        {
            var ul = $('<ul/>');
            
            $.each(result.data,function(i,t){
                var tweet = tw.BuildTweet(t);
                tweet.prependTo(ul);
            });
            
            pop.Content(ul);
        }
        else
        {
            pop.AddMessage('No tweets found for this user', true);
        }
            
    }
    
}