<?php 

$params = "";
$reqParams = "";	
if(!empty($_REQUEST['os'])){
	$params .= (empty($params))?'"os":"'.urlencode($_REQUEST['os']).'"':',"os":"'.urlencode($_REQUEST['os']).'"';
	$reqParams .= "&os={$_REQUEST['os']}";
}	
if(!empty($_REQUEST['vlists'])){
	$params .= (empty($params))?'"v":['.urlencode($_REQUEST['vlists']).']':',"v":['.urlencode($_REQUEST['vlists']).']';
}	
if(!empty($_REQUEST['ctry'])){
	$params .= (empty($params))?'"ctry":"'.urlencode($_REQUEST['ctry']).'"':',"ctry":"'.urlencode($_REQUEST['ctry']).'"';
	$reqParams .= "&ctry={$_REQUEST['ctry']}";
}
if(!empty($_REQUEST['b'])){
	$params .= (empty($params))?'"b":"'.urlencode($_REQUEST['b']).'"':',"b":"'.urlencode($_REQUEST['b']).'"';
	$reqParams .= "&b={$_REQUEST['b']}";
}
$filterParams = $_SERVER['QUERY_STRING'];
if(!empty($_REQUEST['int']))
	$reqParams .= "&int={$_REQUEST['int']}";
else
	$reqParams .= "&int=t";
$params = (!empty($params))?"&w={".$params."}":"";

$stats = ad::getNetworkGraphStats($adid,$interval,$custominterval,$params);
$networkArr = array();
$impArr = array();
$clkArr = array();
$impPerArr = array();
$clkPerArr = array();
$maxImp = $stats['impression'];
$maxClk = $stats['clicks'];
unset($stats['impression']);
unset($stats['clicks']);
$lableImagesArr = "";
$lableRequestsArr = "";
$lableClicksArr = "";
$lableCtrArr = "";
$int = "t";


$cnt = 0;
foreach($stats as $key => $items){
	$networkArr[] = "'".$key."'";
	$impArr[] = $items['imp'];
	$clkArr[] = (!empty($items['clk']))?$items['clk']:0;
	$impPerArr[] = round(($items["imp"]/$maxImp)*100,2);
	$clkPerArr[] = (!empty($items['clk']) && $items['clk'] >0)?round(($items["clk"]/$maxClk)*100,2):0;
	if($lableImagesArr != "")
		$lableImagesArr .= ",";
    $lableImagesArr	.= "'$key':'images/$key.jpg'";
	
	if($lableRequestsArr != "")
		$lableRequestsArr .= ",";
	$lableRequestsArr	.= "'$key':'".number_format($items['imp'])."'";	
	
	if($lableClicksArr != "")
		$lableClicksArr .= ",";
	if(!empty($items['clk']))	
		$lableClicksArr	.= "'$key':'".number_format($items['clk'])."'";
	else	
		$lableClicksArr	.= "'$key':'0'";
		
	if($lableCtrArr != "")
		$lableCtrArr .= ",";

	if(!empty($items['clk']) && $items['imp'] > 0)		
		$lableCtrArr	.= "'$key':'".round(($items["clk"]/$items["imp"])*100,2)."'";	
	else	
		$lableCtrArr	.= "'$key':'0'";	
}

?>

		



<script type="text/javascript">
$(function () {
		
		
		
		var lableImagesArr={		
		<?php echo $lableImagesArr;?>
		}

		var lableRequestsArr={		
		<?php echo $lableRequestsArr;?>
		}

		var lableClicksArr={		
		<?php echo $lableClicksArr;?>
		} 
		
		var lableCtrArr={		
		<?php echo $lableCtrArr;?>
		} 
        $('#networkGraph').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [<?php echo implode(",",$networkArr);?>],
				 labels: {
				   useHTML: true,
            formatter: function() {
                return '<div style="margin-top:5px;"><img src='+lableImagesArr[this.value]+' width="80px" onError="$(this).parent().css(\'padding-bottom\',\'10px\');$(this).parent().html($(this).attr(\'alt\'));" alt="'+this.value+'"/></div><div style="height:30px"><img src="images/btnicon.png" reqparams="?<?php echo $filterParams;?>&nw='+this.value+'" class="button"></div><div class="stats"><div>'+lableRequestsArr[this.value]+'</div><div class="bluetxt">Delivered</div></div><div class="stats"><div>'+lableClicksArr[this.value]+'</div><div class="bluetxt">Clicks</div></div></div><div class="stats"><div>'+lableCtrArr[this.value]+'</div><div class="bluetxt">CTR</div></div></div>';
            },
          
        }
            },
            yAxis: [{
               labels: {
				   enabled: false
				},
                title: {
                    text: ''
                },
				gridLineWidth: 0,
				
            },
			{
               labels: {
				   enabled: false
				},
                title: {
                    text: ''
                },
				gridLineWidth: 0,
				
            }
			],
            tooltip: {
                 
            },
			 plotOptions: {
               column: {
                    pointPadding:0,
                    borderWidth: 0
                },
                    dataLabels: {
                        color: "#0000000",
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return this.y +'%';
                        }
                    },
               series: {
						dataLabels: {
							enabled: true,
							 formatter: function() {
                            return this.y +'%';
                        }
						}
					}
            },
            series: [{
                name: 'Impressions',
                data:[<?php echo implode(",",$impPerArr);?>],
				yAxis:0,
				pointWidth:35
    
            }, {
                name: 'Clicks',
                data:  [<?php echo implode(",",$clkPerArr);?>],
				yAxis:1,
				pointWidth:35
            }
            ],exporting: {
            enabled: false
        },
	colors:['#86b517','#058dc7'],
		 legend: {
					floating:true,
					align: 'right',
					verticalAlign: 'top',
					x: 0,
					y: 0,
					borderWidth:0
				}
        });
		
		
		

    });
</script>

<div id="networkGraph" style="min-width: 200px; height:370px; margin: 0 auto;"></div>



