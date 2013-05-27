
<?php 
require_once('../ad.class.php');
$eventName = $_REQUEST['goal'];
$adid = $_REQUEST['adid'];
$interval = "today";
$custominterval = "";
if(empty($_REQUEST['int'])){
	$interval = "today";
}else if($_REQUEST['int'] == "m"){
	$interval = "month";	
}else if($_REQUEST['int'] == "q"){
	$interval = "quarter";	
}else if($_REQUEST['int'] == "c"){
	$interval = "custom";
	$custominterval['start_date'] = $_REQUEST['start_date'];
	$custominterval['end_date'] = $_REQUEST['end_date'];
}
$data = ad::getEventsGraphStats($adid,$interval,$custominterval,$eventName);
$maxEvents = $data['totalEvents'];
$maxEventVal = 0;
$maxEventNw = "";
$eventsPcHtml = "";
unset($data['totalEvents']);
foreach($data as $key => $item){
	$eventsPc = ($item>0)?round((($item/$maxEvents)*100),2):0;
	$evetnsPcArr[] = "['".$key."',".$eventsPc."]";
	$eventsPcHtml .= "<tr><td>$key</td>
				   <td>".number_format($item)."</td>
				  </tr>";
	if($maxEventVal < $item){
		$maxEventVal = $item;
		$maxEventNw = $key;
	}
}
?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'goalcontainer',
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
                name: 'Goal',
                data: [<?php echo implode(',',$evetnsPcArr);?>]
            }],
			 legend: {
                  	symbolWidth: 10
        },
		
		//colors:['#00cfe7','#bbd20b','#ff65ad','#ffac28','#ff7975']
        });
    });
    
});
    

</script>

	
<div id="goalcontainer" style="width:50%;height: 300px;float:left;"></div>
<div style="width:50%;float:right;">
<div class="totaltraffic" >Total : <span style="color:#FF9A39"> <?php echo number_format($maxEvents);?></span></div>
<table class="sourcetblTraffic" style="width:auto">
	<tr>
	<th>Source</th>
	<th>Events</th>
	</tr>
	<?php echo $eventsPcHtml; ?>
</table>

</div>
<div style="clear:both;margin-bottom:15px"></div>
<div class="highestTraffictop">
</div>
<table class="highestTraffic" style="width:auto">
	<tr>
	<td style="color:#3c3c3c;">Highest:</td>
	<td><img src="images/<?php echo $maxEventNw;?>.jpg" onError="$(this).parent().html('<?php echo $maxEventNw;?>');"></td>
	</tr>
</table>