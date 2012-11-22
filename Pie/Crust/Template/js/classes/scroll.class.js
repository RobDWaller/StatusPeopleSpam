function Scroll()
{
    
    this.To = function(id,time,minus)
    {
        $('html, body').animate({
            scrollTop: $(id).offset().top-minus
        }, time);
    }
    
}