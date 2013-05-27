<?php
$params = "";
if(!empty($_REQUEST['nw']))
	$params = '"nw":"'.urlencode($_REQUEST['nw']).'"';
	
if(!empty($_REQUEST['os'])){
	$params .= (empty($params))?'"os":"'.urlencode($_REQUEST['os']).'"':',"os":"'.urlencode($_REQUEST['os']).'"';
}	
if(!empty($_REQUEST['vlists'])){
	$params .= (empty($params))?'"v":['.urlencode($_REQUEST['vlists']).']':',"v":['.urlencode($_REQUEST['vlists']).']';
}	
if(!empty($_REQUEST['ctry'])){
	$params .= (empty($params))?'"ctry":"'.urlencode($_REQUEST['ctry']).'"':',"ctry":"'.urlencode($_REQUEST['ctry']).'"';
}
if(!empty($_REQUEST['b'])){
	$params .= (empty($params))?'"b":"'.urlencode($_REQUEST['b']).'"':',"b":"'.urlencode($_REQUEST['b']).'"';
}
$params = (!empty($params))?"&gr=[]&w={".$params."}":"";
$hourStats = ad::getHourGraphStats($adid,$interval,$custominterval,$params);
?>
<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Time of Day</span></td>
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" >
	<table style="width:100%;" class="timeofdaytbl" cellspacing="0" cellpadding="0" >
	<tr>
	<th></th>
	<th>Time</th>
	<th>Delivered</th>
	<th>Clicks</th>
	<th>CTR %</th>
	<th></th>
	</tr>
	<tr>
	<td rowspan="12" align="center" class="Bigtext" style="font-size:25px;color:#272727;"><p><img src="images/AM_icon.jpg"></p>
	AM</td>
	<td><div>00:00-00:59</div></td>
	<td><div><?php echo number_format($hourStats['imp'][0]);?></div></td>
	<td><div><?php echo number_format($hourStats['clk'][0]);?></div></td>
	<td>
		<div>
			<?php 
				
				echo ($hourStats['imp'][0]>0)?round(($hourStats['clk'][0]/$hourStats['imp'][0])*100,2):0;
			?>
		</div>
	</td>
	<td rowspan="25" style="width:50%" id='hourGraphCntr'></td>
	</tr>
	<?php
	for($i=1;$i<24;$i++)
	{
	echo "<tr>";
	if($i==12)
	echo "<td align='center' style='vertical-align:top'><img src='images/divider.jpg'></td>";
	if($i==13)
	echo "<td rowspan='13' align='center' class='Bigtext' style='width:10%;font-size:25px;color:#272727'><p><img src='images/PM_icon.jpg'></p>PM</td>";
	echo "<td><div>".sprintf("%02s", $i).":00-".sprintf("%02s", $i).":59</div></td>";
	echo "<td><div>".number_format($hourStats['imp'][$i])."</div></td>
	<td><div>".number_format($hourStats['clk'][$i])."</div></td>
	<td><div>".(($hourStats['imp'][$i]>0)?round(($hourStats['clk'][$i]/$hourStats['imp'][$i])*100,2):0)."</div></td>
	</tr>";
	}
	?>
	</table>
	</div>
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>

<script type="text/javascript">

$(function () {
	$('#hourGraphCntr').highcharts({
		chart: {
		margin:-10
		},
		
		title: {
			style:{display:'none'}
		},
		xAxis: {   
			categories: ['1', '2', '3', '4', '5','6', '7', '8', '9', '10','11', '12', '13', '14', '15','16', '17', '18', '19', '20', '21', '22', '23', '24'],
			lineWidth: 0,
			minorGridLineWidth: 0,
			minorTickPosition: 'inside',
			 
			labels: {
			   enabled: false
			},
			minorTickLength: 0,
			tickLength: 0
		},
		yAxis: [{   
			
			lineWidth: 0,
				 
			labels: {
			   enabled: false
			},
			gridLineWidth: 0,
			
		},
		{   
			
			lineWidth: 0,
				 
			labels: {
			   enabled: false
			},
			gridLineWidth: 0,
			
		}
		],
		tooltip: {
			 enabled:false
		},
		
		legend:{enabled:false},
	
		series: [{
			type: 'bar',
			name: 'Impressions',
			data: [<?php echo implode(",",$hourStats['imp']);?>],
			yAxis:0
		}, {
			type: 'area',
			name: 'Clicks',
			data:[<?php echo implode(",",$hourStats['clk']);?>],
			yAxis:1,
			marker: {
				lineWidth: 2,
				lineColor: '#058dc7',
				fillColor: '#058dc7',
				radius:2
			},
			
		}],
		colors:['#86b517','#058dc7'],
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