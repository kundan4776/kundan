<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">

$(function () {
        $('#container').highcharts({
            chart: {
			margin:-10
            },
			
            title: {
				style:{display:'none'}
            },
            xAxis: {   
                categories: ['1', '2', '3', '4', '5','6', '7', '8', '9', '10','11', '12', '13', '14', '15','16', '17', '18', '19', '20', '21', '22', '23', '24'],
				lineWidth: 0,
				minorGridLineWidth: 0,
				minorTickPosition: 'inside',
			     
				labels: {
				   enabled: false
				},
				minorTickLength: 0,
				tickLength: 0
            },
			yAxis: {   
                
				lineWidth: 0,
				     
				labels: {
				   enabled: false
				},
				gridLineWidth: 0,
				
            },
            tooltip: {
                 enabled:false
            },
            
			legend:{enabled:false},
		
            series: [{
                type: 'bar',
                name: 'Impressions',
                data: [8, 7,6 ,8,3,5,3,7,5, 6, 8, 7, 8, 7,6 ,8,3,5,3,7,5, 6, 8, 7]
            }, {
                type: 'area',
                name: 'Clicks',
                data:[3, 2, 3, 3,1,2,3,4,3,3,2, 1, 3, 3,1, 7,3 ,2,3,1,3,2,1, 2],
                marker: {
                	lineWidth: 2,
                	lineColor: '#058dc7',
                	fillColor: '#058dc7',
					radius:2
                },
				
            }],
			colors:['#86b517','#058dc7'],
			        legend: {
					align: 'right',
					verticalAlign: 'top',
					x: 0,
					y: 100
				},
				
			exporting: {
            enabled: false
        }
        });
    }); 
    
		</script>
	</head>
	<body>
<script src="../scripts/highcharts.js"></script>
<div id="container" style="min-width: 400px; height:655px; margin:0px;"></div>

	</body>
</html>
