function Charts(){this.BuildChart=function(a){if(a.code==201){options={chart:{renderTo:"chart",defaultSeriesType:"line"},title:{text:"",x:0,align:"right",style:{color:"#fe7d1d",fontSize:"12px"}},xAxis:{categories:[],labels:{enabled:true},reversed:true},yAxis:{title:{text:"",style:{color:"#36b6d5"}},plotLines:[{value:0,width:1}],min:0,showFirstLabel:true},tooltip:{formatter:function(){return this.series.name+": "+this.y+"%"}},legend:{enabled:false},colors:["#FE1B2A","#fe7d1d","#2AFE1B"],series:[]};$.each(a.data,function(c,d){var e={data:[]};e.name=c;$.each(d,function(f,g){options.xAxis.categories.push(g.date);e.data.push(parseFloat(g.count))});options.series.push(e)});var b=new Highcharts.Chart(options)}}};