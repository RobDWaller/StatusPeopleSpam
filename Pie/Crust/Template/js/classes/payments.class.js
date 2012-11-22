function Payments()
{
    
    this.RoundNumber = function(num, decimals) 
    { 
        var newnumber = Math.round(num*Math.pow(10,decimals))/Math.pow(10,decimals);
        
        return parseFloat(newnumber); 
    }
    
    this.RecalculateCart = function(currency,months)
    {
        
        var cur = '&pound;';
        var base = 4.99;
        var tax = 1.00;
        
        if (currency == 'USD')
        {
            cur = '&#36;';
            base = 8.99;
            tax = 2.00;
        }
        else if (currency == 'EUR')
        {
            cur = '&euro;';
            base = 6.99;
            tax = 1.50;
        }
        
        var tms = 1;
        var svt = 0;
        
        if (months == 6)
        {
            tms = 5;
            svt = 1;
        }
        else if (months == 12)
        {
            tms = 10;
            svt = 2;
        }
        
        var pay = new Payments();
        
        var st = pay.RoundNumber((tms * base),2);
        var sv = pay.RoundNumber((svt * base),2);
        var tx = pay.RoundNumber((tms * tax),2);
        var tot = pay.RoundNumber((st+tx),2);
        
        $('.currency').html(cur);
        $('#cartmonths').text(months);
        $('#cartsubtotal').text(st.toFixed(2));
        $('#cartsaving').text(sv.toFixed(2));
        $('#carttax').text(tx.toFixed(2));
        $('#carttotal').text(tot.toFixed(2));    
        
        $('#subtotal').val(st.toFixed(2));
        $('#tax').val(tx.toFixed(2));
        $('#months').val(months);
        $('#saving').val(sv.toFixed(2));
        
    }
    
}