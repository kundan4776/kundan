<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'percentclickscontainer',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
				
            },
             title: {
                style:{display:'none'}
            },
            
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: '% Clicks',
                data:  [<?php echo implode(",",$nwTrafficClkPcArr);?>]
            }],
			 legend: {
                  	symbolWidth: 10
        },
		colors:['#00cfe7','#bbd20b','#ff65ad','#ffac28','#ff7975']
        });
    });
    
});
    

</script>
<div id="percentclickscontainer" style="width:100%;height: 300px; margin:auto;text-align:center"></div>
<div class="totaltraffic" >Total Clicks:<span style="color:#FF9A39"> <?php echo number_format($maxClk);?></span></div>
<table class="sourcetblTraffic" >
	<tr>
	<th>Source</th>
	<th>Clicks</th>
	</tr>
	<?php
	echo $nwTrafficClkHtml;
	$highest=0;
	?>
</table>
<div class="highestTraffictop">
</div>
<table class="highestTraffic" style="width:auto">
	<tr>
	<td style="color:#3c3c3c;">Highest Clicks :</td>
	<td><img src="images/<?php echo $maxClkNw;?>.jpg" onError="$(this).parent().html('<?php echo $maxClkNw;?>');"/></td>
	</tr>
</table>

