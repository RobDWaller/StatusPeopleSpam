function Lengths()
{
    this.GetLength = function(message,limit,urls)
    {
        var linklength = 0;
        var newlength = 0;

        if (urls)
        {
            var rg1 = new RegExp('http:\\/\\/([a-zA-Z0-9\\/\\_\\-\\.\\(\\)\\&\\?\\=\\%\\#\\!]*)','g');

            var matches = message.match(rg1);

            if (matches)
            {
                $.each(matches, function(i,v){

                    linklength += v.length;
                    newlength += 25;

                });
            }
        }
        
        var ml = message.length;

        ml = ml - linklength;
        ml = ml + newlength;

        ml = limit - ml;
        
        return ml;
    }
    
    this.StringLength = function(string)
    {
        return string.length;
    }
}