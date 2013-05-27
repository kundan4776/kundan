<?php 
include("ad.class.php");

$adid = "024ec9936837108b988e8355aaebf525";

$adid = $_REQUEST['adid'];
$adInfoData = ad::fetchAdData($adid);
$adDetails = ad::getAdInfo($adInfoData);
$adEvents = ad::getAdEvents($adInfoData);
$custominterval = "";
$interval = "today";
$customReqParams = "";
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
	$customReqParams = "&start_date={$_REQUEST['start_date']}&end_date={$_REQUEST['end_date']}";
}

$ad=new ad($adid,$interval,$custominterval);
$clicks=$ad->clicks;
$requests=$ad->requests;
$stats = $ad::getNetworkGraphStats($adid,$interval,$custominterval,"");
$pagestats = $ad::getNetworkScreenPageStats($adid,$interval,$custominterval,"");

$networkArr = array();//used for graph in charts/engmntbysourceline.php
$impArr = array();//used for graph in charts/engmntbysourceline.php
$clkArr = array();//used for graph in charts/engmntbysourceline.php
$impPerArr = array();//used for graph in charts/engmntbysourceline.php
$clkPerArr = array();//used for graph in charts/engmntbysourceline.php
$nwTrafficImpPcArr = "";//used for pie chart in charts/trafficpie.php 
$nwTrafficImpHtml = "";//used for html in trafficpie.php 
$nwTrafficClkPcArr = "";//used for pie chart in charts/percentclickspie.php 
$nwTrafficClkHtml = "";//used for html in percentclickspie.php 
$maxImp = $stats['impression'];
$maxClk = $stats['clicks'];
unset($stats['impression']);
unset($stats['clicks']);
$nwstatsTbl = "";
$maxImpNw = "";
$maxImpPerNw = 0;
$scrPerVisitArr = array();//use for pie chart in 
$maxClkNw = "";
$maxClkPerNw = 0;
$totalScrPerVisit = 0;
foreach($stats as $key => $items){
	$networkArr[] = "'".$key."'";
	$impArr[] = $items['imp'];
	$clkArr[] = (!empty($items['clk']))?$items['clk']:0;
	$impPer = (!empty($items['imp']))?round(($items["imp"]/$maxImp)*100,2):0;
	
	$clkPer = (!empty($items['clk']) && $items['clk'] >0)?round(($items["clk"]/$maxClk)*100,2):0;
	$firArr[] = (!empty($items['clk']) && $items['imp'] >0)?round(($items["clk"]/$items["imp"])*100,2):0;
	
	$nwstatsTbl .= "<tr><td>{$key}</td>
					<td>".number_format($items['imp'])."</td>
					<td>".number_format($items['clk'])."</td>
					<td>{$firArr[count($firArr)-1]}</td></tr>";
					
	$nwTrafficImpPcArr[] = "['".$key."',".$impPer."]";		
	$nwTrafficImpHtml .= "<tr><td>$key</td>
					   <td>".number_format($items['imp'])."</td>
					  </tr>";	
	$nwTrafficClkPcArr[] = "['".$key."',".$clkPer."]";		
	$nwTrafficClkHtml .= "<tr><td>$key</td>
					   <td>".number_format($items['clk'])."</td>
					  </tr>";	
	if($maxImpPerNw < $items['imp']){
		$maxImpNw = "$key";
		$maxImpPerNw = $items['imp']; 
	}
	
	if($maxClkPerNw < $items['clk']){
		$maxClkNw = "$key";
		$maxClkPerNw = $items['clk']; 
	}
	print_r($pagestats);
	print_r($items);
	$scrPerVisitArr[$key] = (!empty($pagestats[$key]) && !empty($items['clk']) && $items['clk'] > 0)?round(($pagestats[$key]/$items['clk']),2):0;
	$totalScrPerVisit += (!empty($scrPerVisitArr[$key]))?$scrPerVisitArr[$key]:0;
}

//$j=0;
//for($i=0;$i<count($stats['clk']);$i++)
//$firpercent[$j++]=($stats['clk'][$i]/$clicks)*100;

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css" /> 
<title></title>
<style>
.jquerybubblepopup-all-black table{font-size:11px;vertical-align:middle;color:#333333;}
.jquerybubblepopup-all-black td{font-size:11px;vertical-align:middle;color:#333333;}
.jquerybubblepopup-all-black img{font-size:11px;vertical-align:middle;color:#333333;}
.jquerybubblepopup-all-black a{text-decoration:underline}
.button{cursor:pointer;}
</style>
<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
<link href="css/jquery-bubble-popup-v3.css" rel="stylesheet" type="text/css" />
<script src="scripts/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="scripts/jquery-bubble-popup-v3.min.js" type="text/javascript"></script>
<script src="scripts/highcharts.js"></script>
<script type="text/javascript">

$(document).ready(function(){

		$("#leftmenu").height($(document).height());
		setTimeout( function(){
	
		$('#leftmenu').css('left','-120px');},1000); <!-- Change 'left' to 'right' for panel to appear to the right -->

		
		
		$('.button').CreateBubblePopup();
								
			$('.button').mouseover(function(){
	
			var link="ReportBasedOn.php";//$(this).attr("link");
			var text="create report based on this data point";//$(this).attr("text");
			var html="<a href="+link+">"+text+"</a>";
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
									themePath: 'jquerybubblepopup-themes'
								 								 
								  });
		});
		$('.button').click(function(){
		
			window.location="ReportBasedOn.php";
		});
		
		
		$(".viewstat").toggle(function(){
			$("#networksframe").height("380px");
			$(this).html("hide statistics");
		  }, 
		  function(){
			$("#networksframe").height("225px");
			$(this).html("view statistics");
		  });
		
		
		$(".minimise").toggle(function(){
			$(this).parent().parent().parent().parent().find(".whiteContainer").hide();
			$(this).parent().parent().parent().parent().find(".emptywhiteContainer").show();
			$(this).html("<img src='images/maximise.png'>");
			
		  }, 
		  function(){
			$(this).parent().parent().parent().parent().find(".whiteContainer").show();
			$(this).parent().parent().parent().parent().find(".emptywhiteContainer").hide();
			$(this).html("-");
		  });  
		  
		 
		  $("#selectgoal").change(function(){
		  var goal=$(this).val();
		  var param=encodeURIComponent(goal)
		  if(param!="-1")
		  $("#goalDiv").load("charts/goalpie.php?adid=<?php echo $adid."&int=";echo (empty($_REQUEST['int']))?"t":$_REQUEST['int'];echo $customReqParams;?>&goal="+param);
		  
		  });

});



</script>
</head>
<body>
<div class="topheader">
<table class="menu_table" cellspacing="0" cellpadding="0">
<tbody><tr>
<td class="unselMenuhome"><a href="index.php"><img src="images/bonzai_logo.png"></a></td>
<td class="unselMenu" style="width:100px"><a href="">dashboard</a></td>
<td class="unselMenu" style="width:100px"><a href="">campaigns</a></td>
<td class="selMenuhome" style="width:100px"><a href="">reports</a></td>
<td class="unselMenu" style="width:100px"><a href="">settings</a></td>
<td align="right" style="width:60%;color:#929292;text-align:right"><a href="">john_doe@avantis.com</a></td>
</tr>
</tbody></table>
</div>
<div style="clear:both"></div>
<?php
include("leftMenu.php");
?>
<div class="container" id="maincontainer">
<div class="header" >
<table style="width:100%">
<col width="45%">
<col width="55%">
<tr>
<td><span class="pagetitle"><?php echo $adDetails['name'];?></span></td>
<td align="right" >
<ul id="menu">
<li ><a href='publisher.php?int=t&adid=<?php echo $adid;?>' class='<?php echo ($interval == "today")?'selectedMenu':'';?>'>today</a></li>
<li ><a href='publisher.php?int=m&adid=<?php echo $adid;?>' class='<?php echo ($interval == "month")?'selectedMenu':'';?>'>month</a></li>
<li ><a href='publisher.php?int=q&adid=<?php echo $adid;?>' class='<?php echo ($interval == "quarter")?'selectedMenu':'';?>'>quarter</a></li>
<li ><a href='publisher.php?int=c&adid=<?php echo $adid;?>' class='<?php echo ($interval == "custom")?'selectedMenu':'';?>'>custom</a></li> 
</ul>
</td>
</tr>
</table>
</div>
<div class="container boxshadow" style="height:350px;margin-top:20px;width:926px">
<?php 
	$adEvents = ad::getAdEvents($adInfoData);
	include("charts/engmntbysourceline.php");
?>
</div>
<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Engagement by Source  </span></td>
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" >
	<table class="sourcetbl">
	<tr>
	<th>Source</th>
	<th>Impressions</th>
	<th>FIR</th>
	<th>FIR %</th>
	</tr>
	
	<?php
	echo $nwstatsTbl;
	?>
	
	</table>
	</div> 
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>



<div class="blueContainer" style="display:inline-block;width:47%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>% Traffic </span></td>
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" style="padding:0px;text-align:center">
	<?php include("charts/trafficpie.php");?>

	</div>
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>
<div class="blueContainer" style="float:right;width:47%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>% Clicks </span></td>
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" style="padding:0px;text-align:center">
	<?php include("charts/percentclickspie.php");?>
	</div>
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>


<div class="blueContainer" style="display:inline-block;width:47%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Screens/Visit </span></td>
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" style="padding:0px;text-align:center">
	<?php include("charts/screenpervisitpie.php");?>

	</div>
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>
<div class="blueContainer" style="float:right;width:47%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Time Spent </span></td>
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" style="padding:0px;text-align:center">
	<?php include("charts/timespentpie.php");?>
	</div>
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>

<div class="blueContainer" style="width:100%;margin:50px 0px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Goal </span></td>
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" style="padding:0px;text-align:center">
	<div style="text-align:left;padding:20px">
				<select name="goal" id="selectgoal">
					  <option value="-1">Select Goal</option>
					  <?php
						for($i=0;$i<count($adEvents);$i++){
							echo "<option value='{$adEvents[$i]['id']}'>{$adEvents[$i]['name']}</option>";
						}
					  ?>
				</select>
	</div>	
	<div id="goalDiv">
	<?php //include("charts/goalpie.php");?>
	</div>
	</div>
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>
</div>
</body>
</html>