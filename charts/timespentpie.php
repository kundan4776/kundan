<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'graphcontainer',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
				
            },
             title: {
                style:{display:'none'}
            },
            
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
					
					
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Time Spent',
                data: [
                    ['Adfonic',   33],
                    ['bonzai',       34],
                    /*{
                        name: 'Chrome',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },*/
                    ['Inmobi',    42],
                    ['Madvertise',     24],
                    
                ]
            }],
			 legend: {
                  	symbolWidth: 10
        },
		colors:['#00cfe7','#bbd20b','#ff65ad','#ffac28','#ff7975']
        });
    });
    
});
    

		</script>

<div id="graphcontainer" style="width:100%;height: 300px; margin:auto;text-align:center"></div>
<div class="totaltraffic" >Total Time Spent:<span style="color:#FF9A39"> 55</span></div>
<table class="sourcetblTraffic" style="width:65%">
	<tr>
	<th>Source</th>
	<th>Time Spent</th>
	</tr>
	<tr>
	<td>Adfonic</td>
	<td>33</td>
	</tr>
	<tr>
	<td>bonzai</td>
	<td>34</td>
	</tr>
	<tr>
	<td>Madvertise</td>
	<td>42</td>
	</tr>
	<tr>
	<td>Madhouse</td>
	<td>24</td>
	</tr>
</table>
<div class="highestTraffictop">
</div>
<table class="highestTraffic" style="width:auto">
	<tr>
	<td style="color:#3c3c3c;">Highest Time Spent :</td>
	<td><img src="images/bonzai.jpg"></td>
	</tr>
</table>

