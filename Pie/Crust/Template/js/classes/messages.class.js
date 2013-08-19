function Messages()
{
    
    this.Build = function (type,messages,where)
    {
        if ($('#'+type+'message').length)
        {
            $('#'+type+'message').remove();
        }
        
        var ul = $('<ul/>');

        $.each(messages,function(i,m){
            var li = $('<li/>');
            li.html(m);
            li.appendTo(ul);
        });

        var div1 = $('<div class="'+type+'message bree" id="'+type+'message"/>');
        var div2 = $('<div class="e1"/>');
        div2.html('<img src="/Pie/Crust/Template/img/'+type+'_icon.png" />');
        div2.appendTo(div1);
        var div3 = $('<div class="e2 s0"/>');
        ul.appendTo(div3);
        div3.appendTo(div1);
        var div4 = $('<div class="e3 s0 '+type+'close"/>');
        div4.html('<span id="'+type+'close">X</span>');
        div4.appendTo(div1);

        div1.insertAfter(where);
        
		function Remove()
		{
			var ms = new Messages();
			ms.CloseMessages(['#'+type+'message']);
		}
		
        var st2 = setTimeout(Remove,15000);
    }
    
    this.DeleteCheck = function(id)
    {
        var pop = new Popup();
                
        pop.Loader();
        pop.AddMessage('Are You Sure?');

        var div = $('<div/>');

        var input = $('<input id="deletedata" type="hidden"/>');
        input.val(id);
       
        input.appendTo(div);

        var ul = $('<ul class="nav"/>');

        var li1 = $('<li id="deleteyes" class="microbutton pointer"/>');
        li1.text('Yes');
        li1.appendTo(ul);

        var li2 = $('<li id="deleteno" class="microbutton pointer"/>');
        li2.text('No');
        li2.appendTo(ul);

        ul.appendTo(div);

        pop.Content(div);
    }
    
	this.CloseMessages = function(messages)
	{
		$.each(messages,function(e,m){
		
			if ($(m).length)
			{
				$(m).fadeOut("slow",function(){$(this).remove();});
			}
			
		});
	}
	
}