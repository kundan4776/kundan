<?php include("ad.class.php");

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
	$customReqParams = "&start_date={$_REQUEST['start_date']}&end_date={$_REQUEST['end_date']}";
}

if(isset($_REQUEST['adid']))
	$adid=$_REQUEST['adid'];
	
$tclass=$mclass=$qclass=$cclass="";
if($interval=="today")
	$tclass="class='selectedMenu'";
else if($interval=="month")
	$mclass="class='selectedMenu'";
else if($interval=="quarter")
	$qclass="class='selectedMenu'";
else if($interval=="custom")
	$cclass="class='selectedMenu'";
			

$data = ad::getPageViewsStats($adid,$interval,$custominterval);

$adData = ad::fetchAdData($adid);
$adDetails = ad::getAdInfo($adData);
$adEvents = ad::getAdEvents($adData);
$components = ad::getComponentArrByAdId($adid,$adData);
$data['pagenames'] = ad::getComponentNameArrById($data['componentids'],$components);
$hourlydata = ad::getPageViewsHourStats($adid,$interval,$custominterval);
$topEvents = ad::getAllEventsStats($adid,$interval,$custominterval,$adEvents);
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
<script type="text/javascript" src="http://code.highcharts.com/stock/highstock.js"></script>
<script type="text/javascript">

$(document).ready(function(){
		$("#leftmenu").height($(document).height());
		setTimeout( function(){
	
		$('#leftmenu').css('left','-120px');},1000); <!-- Change 'left' to 'right' for panel to appear to the right -->

		
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
<div class="header">
<table style="width:100%">
<col width="45%">
<col width="55%">
<tr>
<td><span class="pagetitle"><?php echo $adDetails['name']?></span></td>
<td align="right" >
<ul id="menu">
<li ><a <?php echo $tclass?> href='EngagementAnalytics.php?int=t&adid=<?php echo $adid;?>'>today</a></li>
<li ><a <?php echo $mclass?> href='EngagementAnalytics.php?int=m&adid=<?php echo $adid;?>'>month</a></li>
<li ><a <?php echo $qclass?> href='EngagementAnalytics.php?int=q&adid=<?php echo $adid;?>'>quarter</a></li>
<li ><a <?php echo $cclass?> >custom</a></li> 
</ul>
</td>
</tr>
</table>
</div>

<div class="container boxshadow" style="height:250px;margin-top:20px;width:926px">
<?php include("charts/engagementline.php");?>
</div>

<div class="boxshadow" style="margin-top:10px;">
<table style="width:100%" class="statistics" cellspacing="0" cellpadding="0">
<tr>
<td style="height:100px;width:50%"><span class='substat'>Total Screen Views</span><span style="font-size:35px;color:#272727;margin-left:20px"><?php echo number_format($data['totalpageviews'])?></span><span><img src="images/change_2.png" style="margin-left:10px"></span></td>
<td style="height:100px;width:50%"><span class='substat'>Avg. Time Spent</span><span style="font-size:35px;color:#272727;margin-left:20px">00:02:06</span><span><img src="images/change_3.png" style="margin-left:10px"></span></td>
</tr>
</table>
</div>

<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Screen View & Exit </span></td>
	<td class="viewstat">view statistics</td>   
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" >
		<div  style="height: 220px;width:100%">
			<?php include("charts/screenviewcolumn.php");?>
		</div>
	</div> 
	<div class="emptywhiteContainer boxshadow" >
	</div>	
</div>

<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Depth of Visit </span></td>
	<td class="minimise">-</td>
	</tr></table>
	
	<table style="width:100%;padding:0px" cellspacing="0" cellpadding="0" class="whiteContainer boxshadow">
		<tr>
		<td><div style="margin-top:20px;height:210px">
			<?php include("charts/depthofvisit.php"); ?>
		</div></td>
		</tr>	
		<tr>
		<td><div style="padding:20px 20px 20px 0px;margin:0px 20px;border-top:1px solid #D9D9D9;font-size:22px;color:#3c3c3c" align="center">Most People Visited : <span style="color:#FF9A39">
		<?php echo ad::getMax($data['depth']);?> Pages</span></div></td>
		</tr>	 
	</table>
	<div class="emptywhiteContainer boxshadow" >
	</div>	
</div>

<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Frequency Distribution About Median Time<img src="images/btnicon.png" style="margin-left:5px" class="button"> </span></td>
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" >
	<table style="width:100%;" class="timeofdaytbl" cellspacing="0" cellpadding="0" >
	<tr>
	<th></th>
	<th>Time</th>
	<th>Views</th>
	<th></th>
	</tr>
	<tr>
	<td rowspan="12" align="center" class="Bigtext" style="font-size:25px;color:#272727;width:15%;"><p><img src="images/AM_icon.jpg"></p>
	AM</td>
	<td><div>00:00-00:59</div></td>
	<td><div><?php echo $hourlydata[0]?></div></td>
	
	<td rowspan="25" style="width:55%;vertical-align:top">
	<?php include("charts/frequencyDist.php");?>
	</td>
	</tr>
	<?php
	for($i=1;$i<24;$i++)
	{
	echo "<tr>";
	if($i==12)
	echo "<td align='center' style='vertical-align:top'><img src='images/divider.jpg'></td>";
	if($i==13)
	echo "<td rowspan='13' align='center' class='Bigtext' style='width:10%;font-size:25px;color:#272727'><p><img src='images/PM_icon.jpg'></p>PM</td>";
	echo "<td><div>".sprintf("%02s", $i).":00-".sprintf("%02s", $i).":59</div></td>
	<td><div>{$hourlydata[$i]}</div></td>";
	?>
	
	
	</tr>
	<?php }?>
	</table>
	</div>
	<div class="emptywhiteContainer boxshadow" >
	</div>
</div>


<div class="blueContainer" style="width:100%;margin:50px 0px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Top Events</span></td>
	<td class="minimise">-</td>
	</tr></table>	
	<table style="width:100%;padding:0px" cellspacing="0" cellpadding="0" class="whiteContainer boxshadow">
		<tr>
		<td>
			<div style="margin-top:20px">
				<?php include("charts/topevents.php"); ?>
			</div>
		</td>
		</tr>			
	</table>
		<div class="emptywhiteContainer boxshadow" >
	</div>
</div>

</div>
</body>
</html>