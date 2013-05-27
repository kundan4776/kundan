<?php
	$analyticsPage = (strstr($_SERVER['PHP_SELF'],"index.php") != false || strstr($_SERVER['PHP_SELF'],"ReportBasedOn.php") != false)?"selectedTitle":"";
	$engagement = (strstr($_SERVER['PHP_SELF'],"EngagementAnalytics.php") != false)?"selectedTitle":"";
	$flow = (strstr($_SERVER['PHP_SELF'],"flow.php") != false)?"selectedTitle":"";
	$publisher = (strstr($_SERVER['PHP_SELF'],"publisher.php") != false)?"selectedTitle":"";
	
?>
<div id="leftmenu">
		<div class="leftMenuTtlOutDiv">
			<div class="leftMenuTtlDiv menuSltBox <?php echo $analyticsPage;?>" id="dashboard">
				<a href="index.php?adid=<?php echo $adid;?>&int=t" class="pagelink">
					<span class="leftMenuTextSpan">
					<img src="images/dashboard.png" style="margin-right:5px;position: relative;top: 5px;">AD Analytics</span>
					<?php echo ($analyticsPage != "selectedTitle")?'<img src="images/white-arrow.png" class="rightMenuTextSpan" style="display:inline">':"";?>
				</a>	
			</div>
        </div> 
       <div class="leftMenuTtlOutDiv" style="border-top:1px solid #1c1d1e;border-bottom:#424242">
			<div class="leftMenuTtlDiv menuSltBox <?php echo $engagement;?>" id="dashboard">
				<a href="EngagementAnalytics.php?adid=<?php echo $adid;?>&int=t" class="pagelink">
					<span class="leftMenuTextSpan">
					<img src="images/dashboard.png" style="margin-right:5px;position: relative;top: 5px;">Engagement</span>
					<?php echo ($engagement != "selectedTitle")?'<img src="images/white-arrow.png" class="rightMenuTextSpan" style="display:inline">':"";?>
				</a>	
			</div>
        </div>
		
		<div class="leftMenuTtlOutDiv">
			<div class="leftMenuTtlDiv menuSltBox <?php echo $flow;?>" id="dashboard">
				<a href="index.php&int=t" class="pagelink">
					<span class="leftMenuTextSpan">
					<img src="images/dashboard.png" style="margin-right:5px;position: relative;top: 5px;">Flow</span>
					<?php echo ($flow != "selectedTitle")?'<img src="images/white-arrow.png" class="rightMenuTextSpan" style="display:inline">':"";?>
				</a>	
			</div>
        </div>
		<div class="leftMenuTtlOutDiv">
			<div class="leftMenuTtlDiv menuSltBox <?php echo $publisher;?>" id="dashboard">
				<a href="publisher.php?adid=<?php echo $adid;?>&int=t" class="pagelink">
					<span class="leftMenuTextSpan">
					<img src="images/dashboard.png" style="margin-right:5px;position: relative;top: 5px;">Publisher</span>
					<?php echo ($publisher != "selectedTitle")?'<img src="images/white-arrow.png" class="rightMenuTextSpan" style="display:inline">':"";?>
				</a>	
			</div>
        </div>

</div>