<script type="text/javascript">

$(function () {
        $('#freqencyDist').highcharts({
            chart: {
			margin:-10
            },
			
            title: {
				style:{display:'none'}
            },
            xAxis: {   
                /*categories: ['1', '2', '3', '4', '5','6', '7', '8', '9', '10','11', '12', '13', '14', '15','16', '17', '18', '19', '20', '21', '22', '23', '24'],*/
				 categories: ['00:00-00:59', '01:00-01:59', '02:00-02:59', '03:00-03:59', '04:00-04:59','05:00-05:59', '06:00-06:59', '07:00-07:59', '08:00-08:59', '09:00-09:59','10:00-10:59', '11:00-11:59', '12:00-12:59', '13:00-13:59', '14:00-14:59','15:00-15:59', '16:00-16:59', '17:00-17:59', '18:00-18:59', '19:00-19:59', '20:00-20:59', '21:00-21:59', '22:00-22:59', '23:00-23:59'],
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
                name: 'Views',
                data: [<?php echo implode(",",$hourlydata);?>],
           			
            }],
			colors:['#357cbd','#ff61a9'],
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
<div id="freqencyDist" style="min-width: 400px; height:677px; margin:0px"></div>

