<?php 
$params = "";
if(!empty($_REQUEST['nw']))
	$params = '"nw":"'.urlencode($_REQUEST['nw']).'"';
	
if(!empty($_REQUEST['os'])){
	$params .= (empty($params))?'"os":"'.urlencode($_REQUEST['os']).'"':',"os":"'.urlencode($_REQUEST['os']).'"';
}else{
	$params .= (empty($params))?'"os":"'.urlencode($sltOs).'"':',"os":"'.urlencode($sltOs).'"';
}	
if(!empty($_REQUEST['v'])){
	$params .= (empty($params))?'"v":"'.urlencode($_REQUEST['v']).'"':',"v":"'.urlencode($_REQUEST['v']).'"';
}	
if(!empty($_REQUEST['ctry'])){
	$params .= (empty($params))?'"ctry":"'.urlencode($_REQUEST['ctry']).'"':',"ctry":"'.urlencode($_REQUEST['ctry']).'"';
}
if(!empty($_REQUEST['b'])){
	$params .= (empty($params))?'"b":"'.urlencode($_REQUEST['b']).'"':',"b":"'.urlencode($_REQUEST['b']).'"';
}
$filterParams = $_SERVER['QUERY_STRING'];
$params = (!empty($params))?"&w={".$params."}":"";
$stats = ad::getOsVersionsGraphStats($adid,$interval,$custominterval,$params);
?>
<script type="text/javascript">

$(function () {
		var osDetails = {<?php echo $stats['labelDetailsArr']?>};
        $('#<?php echo str_replace(" ","",$sltOs);?>Versions').highcharts({
            chart: {
			 type: 'column',
			marginTop:-10
            },
			
            title: {
				style:{display:'none'}
            },
            xAxis: {   
               categories: [<?php echo implode(",",$stats['categories'])?>],
				labels: {
				   useHTML: true,
					formatter: function() {
						return '<div style="height:30px;">'+this.value+'</div><div style="height:30px"><img src="images/btnicon.png" reqparams="?<?php echo $filterParams;?>&os='+encodeURIComponent('<?php echo $sltOs;?>')+'&v='+encodeURIComponent(this.value)+'&vlists='+encodeURIComponent(osDetails[this.value]['versions'])+'" class="button"></div><div class="stats"><div>'+osDetails[this.value]['imp']+'</div><div class="bluetxt">Delivered</div></div><div class="stats"><div>'+osDetails[this.value]['clk']+'</div><div class="bluetxt">Clicks</div></div><div class="stats"><div>'+osDetails[this.value]['ctr']+'</div><div class="bluetxt">CTR</div></div>';
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
                 enabled:false
            },
            
			legend:{enabled:true},
		
            series: [{
                
                name: 'Impressions',
                data: [<?php echo implode(",",$stats['impression'])?>],
				yAxis:0,
				pointWidth: 35
            }, {
                
                name: 'Clicks',
                data:[<?php echo implode(",",$stats['clicks'])?>],
				yAxis:1,
                marker: {
                	lineWidth: 2,
                	lineColor: '#058dc7',
                	
					radius:2
                },
				pointWidth: 35
				
            }],
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
			colors:['#86b517','#058dc7'],
			        legend: {
					floating:true,
					align: 'right',
					verticalAlign: 'top',
					x: 0,
					y: 0
				},
				
			exporting: {
            enabled: false
        }
        });
    }); 
    
</script>
<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'><?php echo $sltOs;?> OS Versions </span></td>
	<td class="viewstat osverstats" id='osstats_<?php echo str_replace(" ","",$sltOs);?>'>view statistics</td>   
	<td class="minimise">-</td>
	</tr></table>	
	<table style="width:100%;padding:0px" cellspacing="0" cellpadding="0" class="whiteContainer boxshadow">
		<tr>
		<td>
			<div style="margin-top:20px;margin-left:50px">
				<div id='osstats_<?php echo str_replace(" ","",$sltOs);?>_cntr' style='height:357px;overflow:hidden;'>
					<div id="<?php echo str_replace(" ","",$sltOs);?>Versions" style="min-width: 400px; height:500px; margin:0px;"></div>
				</div>	
			</div>
		</td>
		</tr>	
	</table>	
</div>

