<?php 
if(count($data["depth"])>6)
{
	$minPoints=count($data["depth"])/6;
	$scrollEnable="true";
}else
{
	$minPoints=0;
	$scrollEnable="false";
}
?>
<script type="text/javascript">
$(function () {
			
        $('#depthofvisit').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
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
            xAxis: {
                categories:[<?php echo implode(",",$data["depth_pages"]);?>],
				 labels: {
				 
            formatter: function() {
                return '<div style="margin-top:5px;font-size:15px">'+this.value+'</div>';
            },
            useHTML: true
        },
			 min:<?php echo $minPoints;?>,
            },
            yAxis: {
               labels: {
				   enabled: false
				},
                title: {
                    text: ''
                },
				gridLineWidth: 0,
				
            },
            tooltip: {
                 
            },
            
			
            
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
                            return  Math.round((this.y/<?php echo $data['depth_total']?>)*100) +'%';
                        }
						},
						pointWidth:30,
					}
            },
            series: [{
                name: 'Visitors',
              data: [<?php echo implode(",",$data["depth"]);?>]
    
            }
            ],exporting: {
            enabled: false
        },
	colors:['#00d5ea'],
		 legend: {
					align: 'right',
					verticalAlign: 'top',
					x: 0,
					y: 0,
					borderWidth:0,
					floating:true	
				}
        });
		
		
		$('.button').CreateBubblePopup();
								
			$('.button').mouseover(function(){
	
			var link=$(this).attr("link");
			var text=$(this).attr("text");
			var html="<a class='link'>"+text+"</a>";
			$(this).ShowBubblePopup({
									selectable: true,
									position : 'bottom',
									align	 : 'center',
									
									innerHtml: html,

									innerHtmlStyle: {
														color:'#FFFFFF', 
														'text-align':'center'
													},

									themeName: 'all-black',
									themePath: '../jquerybubblepopup-themes'
								 								 
								  });
		});
		
		//$(".link").

    });
    

		</script>
<div id="depthofvisit" style="min-width: 400px; height:200px; margin: 0 auto"></div>
