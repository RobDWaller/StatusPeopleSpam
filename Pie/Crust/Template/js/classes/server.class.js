function Server()
{
    
    this.CallServer = function(type,response,url,data,route,vars)
    {
        $.ajax({
            type: type,
            url: url,
            dataType: response,
            data: data,
            cache: false,
            statusCode: {
                404: function () {
                    alert('Page not found!');
                }
            },
            success: function (result) {

                var cm = route.split('_');
                var c = cm[0];
                var m = cm[1];
                
                var myclass = new window[c]();
                
                myclass[m](result,vars);             
                    
            },
            complete: function (xhr, textStatus) {
                console.log(xhr.status);
                console.log(xhr.responseText);
            }
        });
    }

}