
<?php
$data = ad::getOsGraphStats($adid,$interval,$custominterval);
$reqParams = "adid=$adid";
if(!empty($_REQUEST['int']))
	$reqParams .= "&int={$_REQUEST['int']}";
else
	$reqParams .= "&int=t";
$tdHtmls = "";
$dataVar = "";
$dataClkVar = "";
foreach($data as $key => $item){
	$os = ($key=="IOS")?"iPhone%20OS":"Android";
	$tdHtmls .= '<td align="center" class="opratingsysTblTd">';
	$tdHtmls .= '<div style="text-align:center;width:100%;margin:0px auto">
	<div class="statistics" style="color:'.$item['color'].'">'.number_format($item['imp']).'</div><div class="substat" style="margin-bottom:20px;color:#333333">Delivered</div>
	<div class="statistics" style="color:'.$item['color'].'">'.number_format($item['clk']).'</div><div class="substat" style="margin-top:5px;color:#333333">Clicks</div>';
	if($key == "others"){
		$tdHtmls .= '<div style="text-align:left;margin-top:27px"><img src="images/'.$key.'.jpg" style="margin-right:5px"></div>
		</div>';   
	}else{
		$tdHtmls .= '<div style="text-align:left;margin-top:27px"><img src="images/'.$key.'.jpg" style="margin-right:5px"><img src="images/btnicon.png" reqparams="?'.$reqParams.'&os='.$os.'" class="button"></div>
		</div>';   
	}
	$tdHtmls .= '</td>';
	
	if($dataVar != ""){
		$dataVar .= ",";
	}
	
	$dataVar .= "{
			y: {$item['impPer']},
			color: '{$item['color']}',
			drilldown: {
				name: '',
				categories: ['$key'],
				data: [{$item['impPer']}],
				color: '{$item['color']}'
			}
		}";
	if(isset($item['clk'])){	
	if($dataClkVar != ""){
		$dataClkVar .= ",";
	}	
	$dataClkVar .= "{
			y: {$item['clkPer']},
			color: '{$item['color']}',
			drilldown: {
				name: '',
				categories: ['$key'],
				data: [{$item['clkPer']}],
				color: '{$item['color']}'
			}
		}";	
	}	
}
$dataVar .= "];";
$dataClkVar .= "];";
?>



<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Operating System <img src="images/btnicon.png" reqparams="?<?php echo $reqParams;?>&allos=All%20OS" style="margin-left:5px" class="button"> </span></td>
	<td class="minimise">-</td>
	</tr></table>
	
	<table style="width:100%;padding:0px" cellspacing="0" cellpadding="0" class="whiteContainer boxshadow">
		<tr>
		<?php
			echo $tdHtmls;
			
		?>
		</td>
		<td><div><div id="osReqGraphData" style="min-width: 150px; height: 155px; margin: 0 auto"></div></div></td>
		<td><div><div id="osClkGraphData" style="min-width: 150px; height: 155px; margin: 0 auto"></div></div></td>
		</tr>	
	</table>
	<div class="emptywhiteContainer boxshadow" >
	</div>
	
</div>

<script type="text/javascript">
$(function () {
    
	<?php
		echo "var data = [".$dataVar;
		echo "var clkdata = [".$dataClkVar;
	?>
	// Build the data arrays
        var browserData = [];
        var versionsData = [];
		
		var browserClkData = [];
        var versionsClkData = [];
		
        for (var i = 0; i < data.length; i++) {
            for (var j = 0; j < data[i].drilldown.data.length; j++) {
                var brightness = 0.2 - (j / data[i].drilldown.data.length) / 5 ;
                versionsData.push({
                    name: data[i].drilldown.categories[j],
                    y: data[i].drilldown.data[j],
                    color: Highcharts.Color(data[i].color).brighten(brightness).get()
                });
            }
        }
		
		browserData.push({
                name: 'Delivered',
                 y: 100,
                color: '#ffffff'
            });
			
		for (var i = 0; i < clkdata.length; i++) {
            for (var j = 0; j < clkdata[i].drilldown.data.length; j++) {
                var brightness = 0.2 - (j / clkdata[i].drilldown.data.length) / 5 ;
                versionsClkData.push({
                    name: clkdata[i].drilldown.categories[j],
                    y: clkdata[i].drilldown.data[j],
                    color: Highcharts.Color(clkdata[i].color).brighten(brightness).get()
                });
            }
        }
		
		browserClkData.push({
                name: 'Clicks',
                 y: 100,
                color: '#ffffff'
            });	
    
        // Create the chart
        $('#osReqGraphData').highcharts({
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            plotOptions: {
                pie: {
                    shadow: false,
                    center: ['50%', '50%']
                }
            },
            tooltip: {
        	    enabled:false
            },
            series: [{
                name: 'IOS',
                data: browserData,
                size: '80%',
                dataLabels: { 
                    color: '#000000',
                    distance: -45
                }
            }, {
                name: 'Android',
                data: versionsData,
                size: '80%',
                innerSize: '85%',
                dataLabels: {
					   distance: 3,
                    formatter: function() {
                        // display only if larger than 1
                        return this.y > 1 ? this.point.name +":"+this.y +'%'  : null;
                    }
                }
            }],exporting: {
            enabled: false
        }
        });
		
		$('#osClkGraphData').highcharts({
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            plotOptions: {
                pie: {
                    shadow: false,
                    center: ['50%', '50%']
                }
            },
            tooltip: {
        	    enabled:false
            },
            series: [{
                name: 'IOS',
                data: browserClkData,
                size: '80%',
                dataLabels: { 
                    color: '#000000',
                    distance: -45
                }
            }, {
                name: 'Android',
                data: versionsClkData,
                size: '80%',
                innerSize: '85%',
                dataLabels: {
					   distance: 3,
                    formatter: function() {
                        // display only if larger than 1
                        return this.y > 1 ? this.point.name +":"+this.y +'%'  : null;
                    }
                }
            }],exporting: {
            enabled: false
        }
        });
        
});

</script>
