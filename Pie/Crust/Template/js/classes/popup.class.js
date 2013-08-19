function Popup()
{
    
    this.BuildPopup = function(data)
    {
        var div1 = $('<div id="loaderfade2"/>');
        
        var box = $("<div/>");
        box.attr('id','popup');

        var close = $("<div/>");
        close.attr('id','popupcloseholder');

        var closelink = $("<a/>");
        closelink.attr('id','popupclose');
        closelink.text("Close");
        closelink.appendTo(close);

        close.appendTo(box);

        var databox = $("<div/>");
        databox.attr('id','popupdata');
        databox.append(data);

        databox.appendTo(box);
        
        box.appendTo(div1);

        div1.appendTo("body");
    }

    this.Loader = function(message)
    {
        var loader = $('<div/>');
        loader.attr('id','popuploader');
        loader.text(message);

        if ($("#popup").length > 0)
        {
            loader.prependTo("#popupdata");
        }    
        else
        {
            this.BuildPopup(loader);
        }
    }

    this.Content = function(data,style)
    {

        if ($('#popupcontent').length > 0)
        {
            $('#popupcontent').remove();    
        }

        var content = $('<div/>');
        content.attr('id','popupcontent');
        content.append(data);

        if (style)
        {
            content.addClass(style);
        }

        content.appendTo("#popupdata");
    }

    this.AddMessage = function(message,error)
    {
        var popupmessage = $('<div/>');

        if (error)
        {
            popupmessage.addClass('persistanterror');
        }
        else
        {
            popupmessage.addClass('persistantsuccess');
        }

        popupmessage.attr('id','popupmessage');

        popupmessage.text(message);

        popupmessage.prependTo("#popupdata");
    }

    this.RemovePopup = function()
    {
        $('#loaderfade2').remove();
//        $("#popup").remove();
    }

    this.RemoveContent = function()
    {
        $("#popupcontent").remove();
    }

    this.RemoveLoader = function()
    {
        $("#popuploader").remove();
    }

    this.RemoveMessage = function()
    {
        $("#popupmessage").remove();
    }
    
    this.TinyLoader = function()
    {
        if (!$('#tinyloader').length)
        {
            var div1 = $('<div id="loaderfade"/>');
            var div2 = $('<div id="tinyloader"><h2>Loading...</h2><img src="http://fakers.statuspeople.com/Pie/Crust/Template/img/287.gif" /></div>');

            div2.appendTo(div1);

            div1.appendTo('body');
        }
    }
    
    this.RemoveTinyLoader = function()
    {
        if ($('#tinyloader').length)
        {
        
            $('#loaderfade').remove();
        
        }
    }
    
	this.RightPopup = function()
	{
		if (!$('#rightpopup').length)
		{
			var div = $('<div id="rightpopup">'
						+'<div><span id="closerightpopup">x</span></div>'
						+'<div id="rightcontent"></div>'
						+'</div>');
			
			div.appendTo('body');
		}
	}
	
	this.CloseRightPopup = function()
	{
		if ($('#rightpopup').length)
		{
			$('#rightpopup').remove();
		}
	}
	
	this.RightContent = function(content)
	{
		$('#rightcontent').html('');
		content.appendTo('#rightcontent');
	}
}