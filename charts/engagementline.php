<script type="text/javascript">
	$(function () {
    
	
	$('#container').highcharts({
            chart: {
			
            },
            title: {
                style:{display:'none'}
            },
            xAxis: {
                categories: [<?php echo implode(",",$data["categories"]);?>],
            },
			   yAxis: {
                min: 0
            },
            tooltip: {
               
            },
            labels: {
                items: [{
                   
                    style: {
                        left: '40px',
                        top: '8px',
                        color: 'black'
                    }
                }]
            },
           series: [{
                type: 'line',
                name: 'Total Page Views',
                data: [<?php echo implode(",",$data["pageviews"]);?>],
				 marker: {
                	radius:3,
					fillColor: '#58c2fc',	
                },
            }],
			colors:['#357cbd'],
			legend: {
                layout: 'horizontal',
                align: 'right',
                verticalAlign: 'top',
                borderWidth: 0  ,
				floating:true				
            },
			exporting: {
            enabled: false
        }
			});
        });
    

		</script>
<div id="container" style="width:100%; height: 200px; margin: 25px auto 0 auto"></div>

