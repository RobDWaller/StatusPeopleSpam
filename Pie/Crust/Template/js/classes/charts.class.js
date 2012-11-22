function Charts()
{
    
    this.BuildChart = function(result)
    {
        
        if (result.code == 201)
        {
            options = {
                chart: {
                    renderTo: 'chart',
                    defaultSeriesType: 'line'
                },
                title: {
                    text: '',
                    x: 0, //center
                    align:'right',
                    style: {color: '#fe7d1d',fontSize:'12px'}
                },
                xAxis: {
                    categories: [],
                    labels: {
                        enabled: true
                    },
                    reversed:true
                },
                yAxis: {
                    title: {
                        text: '',
                        style: {color: '#36b6d5'}
                    },
                    plotLines: [{
                        value: 0,
                        width: 1
                    }],
                    min:0,
                    showFirstLabel:true
                },
                tooltip: {
                    formatter: function () {
                        return this.series.name + ': ' + this.y +'%';
                    }
                },
                legend: {
                    enabled: false
                },
                colors: ['#FE1B2A', '#fe7d1d', '#2AFE1B'],
                series: []
            };

            $.each(result.data, function (i, r) {

                var interactions = {
                    data: []
                };

                interactions.name = i;

                $.each(r, function (i, grdata) {

                    options.xAxis.categories.push(grdata.date);
                    interactions.data.push(parseFloat(grdata.count));

                });

                options.series.push(interactions);

            });

            var chart = new Highcharts.Chart(options);
        }
        
    }
    
}