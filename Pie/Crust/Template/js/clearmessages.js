$(document).ready(function () {

    function clearmessageboxes() {

        $(".error").fadeOut("slow");
        $(".success").fadeOut("slow");

    }

    function MessageTimeout() {

        setTimeout(function () {

            clearmessageboxes();

        }, 20000);
    
    }

    if ($(".error").length || $(".success").length)
    {
        MessageTimeout();
    }

});