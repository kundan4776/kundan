<?php 
include("ad.class.php");
//$ad=new ad("024ec9936837108b988e8355aaebf525","today");
$data= ad::getOsGraphStats("024ec9936837108b988e8355aaebf525","today");
ad::printArr($data);
?>