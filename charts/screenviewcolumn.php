<?php 
if(count($data["sv"])>6)
{
	$minPoints=count($data["sv"])/6;
	$scrollEnable="true";
}else
{
	$minPoints=0;
	$scrollEnable="false";
}

?>
<script type="text/javascript">
$(function () {
			
       var chart= new Highcharts.Chart ( {
			
            chart: {
			renderTo:           'screenviewcolumncontainer',
                type: 'column'
            },
			 scrollbar: {
	    	enabled: <?php echo $scrollEnable?>,
			barBackgroundColor: 'gray',
			barBorderRadius: 10,
			barBorderWidth: 0,
			buttonBackgroundColor: 'gray',
			buttonBorderWidth: 1,
			buttonArrowColor: 'yellow',
			buttonBorderRadius:10,
			rifleColor: 'yellow',
			trackBackgroundColor: 'white',
			trackBorderWidth: 0,
			trackBorderColor: 'silver',
			trackBorderRadius: 1,
			height: 5
	    },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                 categories: [<?php echo implode(",",$data["pagenames"]);?>],
					 min:<?php echo $minPoints;?>,
				 labels: {
				 
				formatter: function() {
					return '<div style="margin-top:5px;font-size:15px">'+this.value+'</div>';
				},
				useHTML: true
			}
            },
            yAxis: [{
               labels: {
				   enabled: false
				},
                title: {
                    text: ''
                },
				gridLineWidth: 0,
				
            },
			{
               labels: {
				   enabled: false
				},
                title: {
                    text: ''
                },
				gridLineWidth: 0,
				
            }],
            
			 plotOptions: {
               column: {
                    pointPadding:0,
                    borderWidth: 0
                },
                    dataLabels: {
                        color: "#0000000",
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return this.y +'%';
                        }
                    },
               series: {
						dataLabels: {
							enabled: true,
							 formatter: function() {
							 if(this.series.name=="Page View")
								var percent=Math.round((this.y/<?php echo $data['totalpageviews'];?>)*100);
							 else
								var percent=Math.round((this.y/<?php echo $data['exit_total'];?>)*100);
                            return  percent+'%';
                        }
						},
						groupPadding: 0.3,pointPadding: 3,pointWidth : 30
					}
            },
            series: [{
                name: 'Page View',
                data:[<?php echo implode(",",$data["sv"]);?>],
				yAxis:0
    
            }, {
                name: 'Exit',
                data:  [<?php echo implode(",",$data["exit"]);?>],
				yAxis:1
     
            }
            ],exporting: {
            enabled: false
        },
		 colors:['#357cbd','#fb5252'],
		 legend: {
					align: 'right',
					verticalAlign: 'top',
					x: 0,
					y: 0,
					borderWidth:0 ,
					floating:true	
				}
        },function(){
			
		});
		
			/*var series = chart.series[0];
			  if (series.data.length) {
			chart.series[0].options.pointWidth = 40;
			chart.series[1].options.pointWidth = 40;
			//alert(chart.series[0].options.pointWidth);
          // chart.setSize (2000, 200);
			}*/
    });
		 

</script>

<div id="screenviewcolumncontainer" style="height: 200px; min-width: 600px; margin: 0 auto"></div>

