<?php
	$scrGraphArr = array();
	$scrGraphHtml = "";
	$nwScrPerVisitPcArr = array();
	$nwScrPerVisitPcHtml = "";
	$maxVisitValue = 0;
	$maxVisitNw = "";
	foreach($scrPerVisitArr as $key => $item){
		$scrVisitPc = ($item>0)?round((($item/$totalScrPerVisit)*100),2):0;
		$nwScrPerVisitPcArr[] = "['".$key."',".$scrVisitPc."]";
		$nwScrPerVisitPcHtml .= "<tr><td>$key</td>
					   <td>".$item."</td>
					  </tr>";
		if($maxVisitValue < $item){
			$maxVisitValue = $item;
			$maxVisitNw = $key;
		}
	}
?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'screenvisitcontainer',
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
                name: 'Screens/Visit',
                data: [<?php echo implode(",",$nwScrPerVisitPcArr)?>]
            }],
			 legend: {
                  	symbolWidth: 10
        },
		colors:['#00cfe7','#bbd20b','#ff65ad','#ffac28','#ff7975']	
        });
    });
    
});
    

		</script>

<div id="screenvisitcontainer" style="width:100%;height: 300px; margin:auto;text-align:center"></div>
<div class="totaltraffic" >Total Screens/Visit:<span style="color:#FF9A39"> <?php echo $totalScrPerVisit;?></span></div>
<table class="sourcetblTraffic" >
	<tr>
	<th>Source</th>
	<th>Screens/Visit</th>
	</tr>
	<?php echo $nwScrPerVisitPcHtml;?>
</table>
<div class="highestTraffictop">
</div>
<table class="highestTraffic" style="width:auto">
	<tr>
	<td style="color:#3c3c3c;">Highest Screens/Visit :</td>
	<td><img src="images/<?php echo $maxVisitNw;?>.jpg" onError="$(this).parent().html('<?php echo $maxVisitNw;?>');"></td>
	</tr>
</table>

