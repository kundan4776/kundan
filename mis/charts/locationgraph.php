<?php
	$params = "";
	$reqParams = "adid=$adid";
	if(!empty($_REQUEST['nw'])){
		$params = '"nw":"'.urlencode($_REQUEST['nw']).'"';
		$reqParams .= "&nw=".urlencode($_REQUEST['nw']);
	}	
		
	if(!empty($_REQUEST['os'])){
		$params .= (empty($params))?'"os":"'.urlencode($_REQUEST['os']).'"':',"os":"'.urlencode($_REQUEST['os']).'"';
		$reqParams = "&os=".urlencode($_REQUEST['os']);
	}	
	if(!empty($_REQUEST['vlists'])){
		$params .= (empty($params))?'"v":['.urlencode($_REQUEST['vlists']).']':',"v":['.urlencode($_REQUEST['vlists']).']';
	}
	
	if(!empty($_REQUEST['b'])){
		$params .= (empty($params))?'"b":"'.urlencode($_REQUEST['b']).'"':',"b":"'.urlencode($_REQUEST['b']).'"';
		$reqParams = "&b=".urlencode($_REQUEST['b']);
	}
	$filterParams = $_SERVER['QUERY_STRING'];
	if(!empty($_REQUEST['int']))
		$reqParams .= "&int={$_REQUEST['int']}";
	else
		$reqParams .= "&int=t";
		
	$params = (!empty($params))?"&w={".$params."}":"";
	
	$countryDetails = ad::getLocationGraphStats($adid,$interval,$custominterval,$params);
	$cnt = 0;
	$cntryTabl = "";
	
	for($i=1;$i<count($countryDetails);$i++){
		$cntryTabl .= "<tr><td><img src='images/btnicon.png' reqparams='?$filterParams&ctry=".urlencode($countryDetails[$i][0])."' class='button'>{$countryDetails[$i][0]}</td><td>".number_format($countryDetails[$i][1])."</td></tr>";
	}
	if($cntryTabl != ""){
		$cntryTabl = "<table class='sourcetblTraffic'>
			<tr>
				<th>Country</th>
				<th>Delivered</th>
			</tr>
			$cntryTabl
		</table>";
	}
?>
<div class="blueContainer" style="width:100%;margin:50px 0px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Geo Location</span></td>
	<td class="viewstat viewstat_lg" >view statistics</td>
	<td class="minimise">-</td>
	</tr></table>  
	<div class="whiteContainer boxshadow">
		<div id="chart_div" style="width: 100%; height: 300px;"></div>
		<div id='locationTbl' style='display:none;width:100%;'>
		<?php 
			echo $cntryTabl;
		?>
		</div>
	</div>
	
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>  
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type='text/javascript'>
     google.load('visualization', '1', {'packages': ['geochart']});
     google.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($countryDetails);?>);

        var options = {
		colorAxis: {colors: ['#b1f14c', '#3a6120'],
		      
		},
		datalessRegionColor:'#dddddd'    
		};

        var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    };
</script>