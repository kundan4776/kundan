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

		$("#leftmenu").hover(
		
		function () {
		  //$('#maincontainer').css('margin-left','200px');
		  },
		function () {
		 // $('#maincontainer').css('margin','0px auto');
		  }

		);
		
		
		//$('.button').CreateBubblePopup();
								
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
		/*$(".viewstat").click(function()
		{
			$("#networksframe").height("380px");
			$(this).html("Hide Stattistics");
		});*/
		
		$(".viewstat_nw").toggle(function(){
			$("#networksframe").height("380px");
			$(this).html("hide statistics");
		  }, 
		  function(){
			$("#networksframe").height("225px");
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
$adid = "024ec9936837108b988e8355aaebf525";
$adid = $_REQUEST['adid'];
include("ad.class.php");
$adInfoData = ad::fetchAdData($adid);
$adDetails = ad::getAdInfo($adInfoData);
$interval = "today";
$custominterval = "";
include("leftMenu.php");
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
<li ><a href='index.php?int=t&adid=<?php echo $adid;?>' class='<?php echo ($interval == "today")?'selectedMenu':'';?>'>today</a></li>
<li ><a href='index.php?int=m&adid=<?php echo $adid;?>' class='<?php echo ($interval == "month")?'selectedMenu':'';?>'>month</a></li>
<li ><a href='index.php?int=q&adid=<?php echo $adid;?>' class='<?php echo ($interval == "quarter")?'selectedMenu':'';?>'>quarter</a></li>
<li ><a href='index.php?int=c&adid=<?php echo $adid;?>' class='<?php echo ($interval == "custom")?'selectedMenu':'';?>'>custom</a></li> 
</ul>
</td>
</tr>
</table>
</div>
<?php 

$ad=new ad($adid,$interval,$custominterval);

?>

<!-- <iframe src="charts/line.php" class="container boxshadow" frameBorder="0" scrolling="no" style="height:350px;margin-top:20px;width:926px"></iframe> -->
<div class="container boxshadow" style="height:350px;margin-top:20px;width:926px">
<?php
	include("charts/line.php");
?>
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
	include("charts/osstats.php");
	include("charts/hourstats.php");
	//include("charts/locationgraph.php");
?>
 
</div>

<script type='text/javascript'>
	var isFirst=true;
	$('.button').live("mouseover",function(){
		if(isFirst){
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
</script>
</body>
</html>

