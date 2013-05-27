<?php
$params = "";
if(!empty($_REQUEST['nw']))
	$params = '"nw":"'.urlencode($_REQUEST['nw']).'"';
	
$params .= (empty($params))?'"os":"Android"':',"os":"Android"';

if(!empty($_REQUEST['vlists'])){
	$params .= (empty($params))?'"v":['.urlencode($_REQUEST['vlists']).']':',"v":['.urlencode($_REQUEST['vlists']).']';
}	
if(!empty($_REQUEST['ctry'])){
	$params .= (empty($params))?'"ctry":"'.urlencode($_REQUEST['ctry']).'"':',"ctry":"'.urlencode($_REQUEST['ctry']).'"';
}
if(!empty($_REQUEST['b'])){
	$params .= (empty($params))?'"b":"'.urlencode($_REQUEST['b']).'"':',"b":"'.urlencode($_REQUEST['b']).'"';
}
$filterParams = $_SERVER['QUERY_STRING'];
$params = (!empty($params))?"&w={".$params."}":"";
$stats = ad::getBrandsGraphStats($adid,$interval,$custominterval,$params);

?>
<script type="text/javascript">
$(function () {
		var brandDetails = {<?php echo $stats['labelDetailsArr']?>};
        $('#brandscolumn').highcharts({
            chart: {
                type: 'column',
				zoomType:'xy'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [<?php echo implode(",",$stats['categories'])?>],
					labels: {
				   useHTML: true,
						formatter: function() {
							return '<div><img width="70px" alt="'+this.value+'" onError="$(this).parent().css(\'padding-bottom\',\'10px\');$(this).parent().html($(this).attr(\'alt\'));" src="images/brands/'+this.value.toLowerCase().replace(/\s/g, '')+'.jpg" /></div><div style="height:30px"><img src="images/btnicon.png" reqparams="?<?php echo $filterParams;?>&b='+this.value+'" class="button"></div><div class="stats"><div>'+brandDetails[this.value]['imp']+'</div><div class="bluetxt">Delivered</div></div><div class="stats"><div>'+brandDetails[this.value]['clk']+'</div><div class="bluetxt">Clicks</div></div><div class="stats"><div>'+brandDetails[this.value]['ctr']+'</div><div class="bluetxt">CTR</div></div>';
						},
					}
				   },
            yAxis: {
               labels: {
				   enabled: false
				},
                title: {
                    text: ''
                },
				gridLineWidth: 0,
				
            },
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
                data:[<?php echo implode(",",$stats['impression'])?>],
				pointWidth: 35
    
            }, {
                name: 'Clicks',
                data:  [<?php echo implode(",",$stats['clicks'])?>],
				pointWidth: 35
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

<div class="blueContainer" style="width:100%;margin-top:50px">
	<table style="width:100%" cellspacing="0" cellpadding="0" class="blueContainerHead"><tr>
	<td><span class='boxtitle'>Brands </span></td>
	<td class="viewstat" id='viewstat_brand'>view statistics</td>   
	<td class="minimise">-</td>
	</tr></table>
	<div class="whiteContainer boxshadow" >
		<div id='brandsgraphCntr' style='height:357px;overflow:hidden;'>
			<div id="brandscolumn" style="min-width: 400px; height:500px; margin: 0 auto"></div>
		</div>	
	</div>  
</div>


