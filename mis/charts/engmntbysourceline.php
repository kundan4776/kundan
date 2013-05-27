<?php 
	
?>
<script type="text/javascript">
		$(function () {
    var chart;
    $(document).ready(function() {
	    chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container1',
                zoomType: 'xy',
				spacingTop:50
            },
            title: {
                text: ''
            },
                 xAxis: {
                categories: [<?php echo implode(",",$networkArr);?>],
					labels: {
				   useHTML: true,
						formatter: function() {
							return '<div style="margin-top:5px;width:100%;"><img style="width:80px" src="images/'+this.value+'.jpg" onError="$(this).parent().css(\'padding-top\',\'5px\');$(this).parent().html($(this).attr(\'alt\'));" alt="'+this.value+'"/></div>';
						},
					  
					}
				   },
            yAxis: [{ // Primary yAxis
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value,0);
                    },
                    style: {
                        color: '#4da6ff'
                    }
                },
                title: {
                    text: 'Impressions',
                    style: {
                        color: '#4da6ff',
						fontSize:'14px'
                    }
                },
				min:0
            },{ // Secondary yAxis
                title: {
                    text: 'FIR',
                    style: {
                        color: '#66cc6f'
                    }
                },
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value,0);
                    },
                    style: {
                        color: '#66cc6f'
                    }
                },
                opposite: true,
				min:0
            },{ // Secondary yAxis
                title: {
                    text: 'FIR %',
                    style: {
                        color: '#f7941d'
                    }
                },
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value,2);
                    },
                    style: {
                        color: '#f7941d'
                    }
                },
                opposite: true,
				min:0
            }],
			
			plotOptions: {
	        series: {
	            pointWidth: 20
	        }
	    },
            tooltip: {
               
            },
            legend: {
                layout: 'horizontal',
				floating:true,
                align: 'left',
                x: 50,
                verticalAlign: 'top',
                y: -50,
                backgroundColor: '#FFFFFF'
            },
            series: [{
                name: 'Impressions',
                color: '#4da6ff',
                type: 'column',
				yAxis: 0,
                data: [<?php echo implode(",",$impArr);?>]
    
            }, {
                name: 'FIR',
                color: '#66cc6f',
                type: 'column',
				yAxis: 1,
                data:  [<?php echo implode(",",$clkArr);?>]
            }, {
                name: 'FIR %',
                color: '#f7941d',
                type: 'line',
				yAxis: 2,
                data:  [<?php echo implode(",",$firArr);?>]
            }],exporting: {
            enabled: false
        }
        });
    });
    
});

		</script>

<div id="container1" style="width:100%; height: 300px; margin: 25px auto 0 auto"></div>
