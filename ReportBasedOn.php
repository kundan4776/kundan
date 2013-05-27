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
	
		$('#leftmenu').css('left','-120px');},1000); 
		
		$(".viewstat_nw").toggle(function(){
			$("#networksframe").height("380px");
			$(this).html("hide statistics");
		  }, 
		  function(){
			$("#networksframe").height("225px");
			$(this).html("view statistics");
			
		});
		
		$("#viewstat_brand").toggle(function(){
			$("#brandsgraphCntr").height("500px");
			$(this).html("hide statistics");
		  }, 
		  function(){
			$("#brandsgraphCntr").height("357px");
			$(this).html("view statistics");
			
		  }); 
		$(".osverstats").toggle(function(){
			$("#"+$(this).attr('id')+"_cntr").height("500px");
			$(this).html("hide statistics");
		  }, 
		  function(){
			$("#"+$(this).attr('id')+"_cntr").height("357px");
			$(this).html("view statistics");
			
	    });   
		
		
		$(".viewstat_lg").toggle(function(){
			$("#locationTbl").css('display','inline-block');
			$(this).html("hide statistics");
		  }, 
		  function(){
			$("#locationTbl").hide();
			$(this).html("view statistics");
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
$adid = $_REQUEST['adid'];
$interval = "today";
$custominterval = "";
require_once("ad.class.php");
$adInfoData = ad::fetchAdData($adid);
$adDetails = ad::getAdInfo($adInfoData);
if($_REQUEST['int'] == "m"){
	$interval = "month";	
}else if($_REQUEST['int'] == "q"){
	$interval = "quarter";	
}else if($_REQUEST['int'] == "c"){
	$interval = "custom";
	$custominterval['start_date'] = $_REQUEST['start_date'];
	$custominterval['end_date'] = $_REQUEST['end_date'];
}

include("leftMenu.php");


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

$ad=new ad($adid,$interval,$custominterval,$params);
$query = $_SERVER['QUERY_STRING'];
?>
?>
<div class="container">
<div class="header">
<table style="width:100%">
<col width="45%">
<col width="55%">
<tr>
<td><span class="pagetitle"><?php echo $adDetails['name'];?></span></td>
<td align="right" >
<ul id="menu">
<li ><a href='ReportBasedOn.php?<?php echo preg_replace('/(?:&|(\?))' . 'int' . '=[^&]*(?(1)&|)?/i', "$1", $query);?>&int=t' class='<?php echo ($interval == "today")?'selectedMenu':'';?>'>today</a></li>
<li ><a href='ReportBasedOn.php?<?php echo preg_replace('/(?:&|(\?))' . 'int' . '=[^&]*(?(1)&|)?/i', "$1", $query);?>&int=m' class='<?php echo ($interval == "month")?'selectedMenu':'';?>'>month</a></li>
<li ><a href='ReportBasedOn.php?<?php echo preg_replace('/(?:&|(\?))' . 'int' . '=[^&]*(?(1)&|)?/i', "$1", $query);?>&int=q' class='<?php echo ($interval == "quarter")?'selectedMenu':'';?>'>quarter</a></li>
<li ><a href='ReportBasedOn.php?<?php echo preg_replace('/(?:&|(\?))' . 'int' . '=[^&]*(?(1)&|)?/i', "$1", $query);?>&int=c' class='<?php echo ($interval == "custom")?'selectedMenu':'';?>'>custom</a></li> 
</ul>
</td>
</tr>
</table>
</div>

<div class="navigationDiv">
	<span class="redtext">Report based on</span>
	<?php
		$filterString = "";
		
		parse_str($_SERVER['QUERY_STRING'],$queryString);
		foreach($queryString as $key => $item){
			if($key == "adid" || $key == "int" || $key == "vlists" || $key == "start_date" || $key == "end_date"){
				continue;
			}
			if($key == "v"){
				$url = preg_replace('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', "$1", $query);
				$url = preg_replace('/(?:&|(\?))' . 'vlists' . '=[^&]*(?(1)&|)?/i', "$1", $url);
			}else
				$url = preg_replace('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', "$1", $query);
			//$url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $query . '&'); 
			//$url = substr($url, 0, -1); 
			
			if($filterString != ""){
				$filterString .= '<div class="arrowsep"><img src="images/arrow.png"></div>';
			}
			$filterString .= '<div class="radiusDiv"><span class="navigationtext">'.$item.'</span> <a href="ReportBasedOn.php?'.$url.'" style="float:right"><img src="images/close.jpg" /></a></div>';
		}
		echo $filterString;
	?>
	
	
	<!--<div class="radiusDiv"><span class="navigationtext">Networks</span> <img src="images/close.jpg" style="float:right"></div>
	<div class="arrowsep"><img src="images/arrow.png"></div>
	<div class="radiusDiv"><span class="navigationtext">Android</span> <img src="images/close.jpg" style="float:right"></div>
	<div class="arrowsep"><img src="images/arrow.png"></div>
	<div class="radiusDiv"><span class="navigationtext">Jellybean</span> <img src="images/close.jpg" style="float:right"></div>-->
</div>
<div class="container boxshadow" frameBorder="0" scrolling="no" style="height:350px;margin-top:20px;width:926px">
	<?php include("charts/line.php");?> 
</div>


<div class="boxshadow" style="margin-top:10px">
<table style="width:100%" class="statistics" cellspacing="0" cellpadding="0">
<tr>
<td><div><?php echo number_format($ad->requests);?></div><div class='substat'>Requests<img src="images/btnicon.png" class="button" link="http://www.gmail.com" text="create report based on this data point"></div></td>
<td><div><?php echo number_format($ad->serverd);?></div><div class='substat'>Served<img src="images/btnicon.png" class="button" link="http://www.google.com" text="create report based on this data point."></div></td>
<td><div><?php echo number_format($ad->delivered);?></div><div class='substat'>Delivered<img src="images/btnicon.png" class="button"></div></td>
<td><div><?php echo number_format($ad->clicks);?></div><div class='substat'>Clicks<img src="images/btnicon.png" class="button"></div></td>
<td><div><?php echo $ad->ctr;?></div><div class='substat'>CTR<img src="images/btnicon.png" class="button"></div></td>
</tr>
</table>
</div>

<?php


if(empty($_REQUEST['nw'])){
?>
<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead">
		<tr>
			<td><span class='boxtitle'>Networks</span></td>
			<td class="viewstat viewstat_nw">view statistics</td>   
			<td class="minimise">-</td>
		</tr>
	</table>
	<div class="whiteContainer boxshadow" >
		<div style="height: 225px;width:100%;overflow:hidden;" id="networksframe">
		<?php include("charts/networkcolumn.php")?>
		</div>
	</div>  
</div>
<?php
}
$osVal = (empty($_REQUEST['os']))?"":$_REQUEST['os'];
if((empty($_REQUEST['os']) || (isset($_REQUEST['os']) && $_REQUEST['os'] != "Android")) && empty($_REQUEST['b']) && empty($_REQUEST['v'])){
	$sltOs = "iPhone OS";
	include("charts/osversionsbar.php");
}
if((empty($_REQUEST['os']) || $_REQUEST['os'] != "iPhone OS") && empty($_REQUEST['v'])){
	$sltOs = "Android";
	include("charts/osversionsbar.php");
}	
if(empty($_REQUEST['b']) && $osVal != "iPhone OS"){	
	include("charts/brandscolumn.php");
}	

?>

<?php 
	include("charts/hourstats.php");
	if(empty($_REQUEST['ctry']))
		include("charts/locationgraph.php");
?>

</div>

<script>
	$(function(){
		var isFirst=true;
		$('.button').live("mouseover",function(){
		if(isFirst)
			{
				$('.button').CreateBubblePopup();
				isFirst=false;
				
			}
			var link="ReportBasedOn.php";
			if($(this).attr('reqparams') != undefined)
				link="ReportBasedOn.php"+$(this).attr('reqparams');
				
			var text="create report based on this data point";
			var html="<a href="+link+" target='_parent'>"+text+"</a>";
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
	
	});
</script>
</body>
</html>