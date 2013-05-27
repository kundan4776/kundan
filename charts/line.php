<?php 
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
$stats = ad::getTotalGraphStats($adid,$interval,$custominterval,$params);
?>

<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container1',
                zoomType: 'xy',
				spacingTop:50
            },
            title: {
                text: ''
            },
            xAxis: [{
                categories: [<?php echo implode(",",$stats["categories"]);?>],
				 labels: {
					<?php if($interval != 'quarter')echo 'rotation: 50,';?>
					y:25,
					color:'#666666'
				}
            }],
            yAxis: [{ // Primary yAxis
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value,0);
                    },
                    style: {
                        color: '#65AED7'
                    }
                },
                title: {
                    text: 'Impressions',
                    style: {
                        color: '#65AED7',
						fontSize:'14px'
                    }
                },
				min:0
            },{ // Secondary yAxis
                title: {
                    text: 'Clicks',
                    style: {
                        color: '#7CAC15'
                    }
                },
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value,0);
                    },
                    style: {
                        color: '#7CAC15'
                    }
                },
                opposite: true,
				min:0
            },{ // Secondary yAxis
                title: {
                    text: 'CTR',
                    style: {
                        color: '#ff1a00'
                    }
                },
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value,2);
                    },
                    style: {
                        color: '#ff1a00'
                    }
                },
                opposite: true,
				min:0
            }],
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x +':<b>'+
                        (this.series.name == 'CTR' ? Highcharts.numberFormat(this.y,2)+'</b>' : Highcharts.numberFormat(this.y,0)+'</b>');
                }
            },
            legend: {
                layout: 'horizontal',
				floating:true,
                align: 'left',
                x: 50,
                verticalAlign: 'top',
                y: -50,
                backgroundColor: '#FFFFFF'
            },
            series: [{
                name: 'Impressions',
                color: '#65AED7',
                type: 'column',
				yAxis: 0,
                data: [<?php echo implode(",",$stats["imp"]);?>]
    
            }, {
                name: 'Clicks',
                color: '#7CAC15',
                type: 'column',
				yAxis: 1,
                data: [<?php echo implode(",",$stats["clk"]);?>]
            }, {
                name: 'CTR',
                color: '#ff1a00',
                type: 'line',
				yAxis: 2,
                data: [<?php echo implode(",",$stats["ctr"]);?>]
            }],exporting: {
            enabled: false
        }
        });
    });
    
});
</script>
<div id="container1" style="width:100%; height: 300px; margin: 25px auto 0 auto"></div>
