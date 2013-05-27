
<style>
.jquerybubblepopup-all-black table{font-size:11px;vertical-align:middle;color:#333333;}
.jquerybubblepopup-all-black td{font-size:11px;vertical-align:middle;color:#333333;}
.jquerybubblepopup-all-black img{font-size:11px;vertical-align:middle;color:#333333;}
.jquerybubblepopup-all-black a{text-decoration:underline;color:#ffffff}
.button{cursor:pointer;}
</style>

<script type="text/javascript">

$(function () {
		var lableImagesArr=new Array();		
		lableImagesArr['adfonic']=['../images/adfonic.jpg'];
		lableImagesArr['inmobi']=['../images/inmobi.jpg'];
		lableImagesArr['madvert']=['../images/madvert.jpg'];
		lableImagesArr['bonzai']=['../images/bonzai.jpg'];
		lableImagesArr['madhouse']=['../images/madhouse.jpg'];	
		var labelImgArr = {<?php echo $topEvents['labelArr'];?>};	
        $('#topEventGraph').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    <?php echo implode(",",$topEvents['categories'])?>
                    ],
				 labels: {
				 
            formatter: function() {
                return '<div style="margin-top:5px;font-size:15px">'+this.value+'</div><div class="stats"><div>'+labelImgArr[this.value]+'</div><div class="bluetxt"></div></div>';
            },
            useHTML: true
        }
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
                            return this.y +'%';
                        }
						},
						groupPadding: 0.4,pointPadding: 0,
					}
            },
            series: [{
                name: 'Event Fired',
                data:[<?php echo implode(",",$topEvents['eventsPc'])?>]
    
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
					borderWidth:0
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
<div id="topEventGraph" style="min-width: 400px; height:200px; margin: 0 auto"></div>
