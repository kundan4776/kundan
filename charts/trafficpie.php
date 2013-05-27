
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'trafficcontainer',
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
                name: '% Traffic',
                data: [<?php echo implode(",",$nwTrafficImpPcArr);?>]
               
            }],
			 legend: {
                  	symbolWidth: 10
        },
		colors:['#00cfe7','#bbd20b','#ff65ad','#ffac28','#ff7975']
        });
    });
    
});
    

		</script>
<div id="trafficcontainer" style="width:100%;height: 300px; margin:auto;text-align:center"></div>

<div class="totaltraffic" >Total Traffic:<span style="color:#FF9A39"> <?php echo number_format($maxImp)?></span></div>
<table class="sourcetblTraffic">
	<tr>
	<th>Source</th>
	<th>Impressions</th>
	</tr>
	<?php
		echo $nwTrafficImpHtml;
	/*$max=$stats['imp'][0];
	$maxindex=0;
	for($i=0;$i<count($stats["nw"]);$i++){
	if($stats['imp'][$i]>$max)
		{
			$max=$stats['imp'][$i];
			$maxindex=$i;
		}
	echo "<tr><td>{$stats['nw'][$i]}</td>
	<td>{$stats['imp'][$i]}</td>
	</tr>";
	}*/
	$highest=0;//$stats['nw'][$maxindex];
	?>
	
</table>
<div class="highestTraffictop">
</div>
<table class="highestTraffic" style="width:auto">
	<tr>
	<td style="color:#3c3c3c;">Highest Traffic :</td>
	<td><img src="images/<?php echo $maxImpNw;?>.jpg" onError="$(this).parent().html('<?php echo $maxImpNw;?>');" /></td>
	</tr>
</table>

