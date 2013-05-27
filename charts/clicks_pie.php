<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
$(function () {
    
        var colors1=['#008bd6','#e36706'],      
            categories = ['IOS', 'Android'],
            name = 'Browser brands',
            data = [{
                    y: 39,
                    color: colors1[0],
                    drilldown: {
                        name: '',
                        categories: ['IOS'],
                        data: [39],
                        color: colors1[0]
                    }
                }, {
                    y: 61,
                    color: colors1[1],
                    drilldown: {
                        name: '',
                        categories: ['Android'],
                        data: [61],
                        color: colors1[1]
                    }
                }
                ];
    
    
        // Build the data arrays
        var browserData = [];
        var versionsData = [];
        for (var i = 0; i < data.length; i++) {
    
            // add browser data
           /* browserData.push({
                name: categories[i],
                y: data[i].y,
                color: data[i].color
            });
			*/
    
            // add version data
            for (var j = 0; j < data[i].drilldown.data.length; j++) {
                var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
                versionsData.push({
                    name: data[i].drilldown.categories[j],
                    y: data[i].drilldown.data[j],
                    color: Highcharts.Color(data[i].color).brighten(brightness).get()
                });
            }
        }
		
		browserData.push({
                name: 'Clicks',
                y: 100,
                color: '#ffffff'
            });
    
        // Create the chart
        $('#container').highcharts({
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            plotOptions: {
                pie: {
                    shadow: false,
                    center: ['50%', '50%']
                }
            },
            tooltip: {
        	    enabled:false
            },
            series: [{
                name: 'IOS',
                data: browserData,
                size: '80%',
                dataLabels: {
                    color: '#000000',
                    distance: -45
                }
            }, {
                name: 'Android',
                data: versionsData,
                size: '80%',
                innerSize: '85%',
                dataLabels: {
					   distance:3,
                    formatter: function() {
                        // display only if larger than 1
                        return this.y > 1 ? "<span style='color:#333333'>"+this.point.name +":"+this.y +"%</span>"  : null;
                    }
                }
            }],exporting: {
            enabled: false
        }
        });
    });
    

		</script>
	</head>
	<body>
<script src="../scripts/highcharts.js"></script>
<div id="container" style="min-width: 150px; height: 155px; margin: 0 auto"></div>

	</body>
</html>
