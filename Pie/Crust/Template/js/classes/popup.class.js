function Popup()
{
    
    this.BuildPopup = function(data)
    {
        var box = $("<div/>");
        box.attr('id','popup')

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

        box.appendTo("body");
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
        $("#popup").remove();
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
    
}