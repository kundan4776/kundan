<?php
require_once("config.php");
class ad
{
	//private $url="";	
	
	public $requests;
	public $serverd;
	public $delivered;
	public $clicks;
	public $ctr;
	
	function __construct($adid,$interval,$custominterval=null,$params=null){
		$totalArr = self::getTotalStats($adid,$interval,$custominterval,$params);
		$this->requests = (!empty($totalArr["request"]))?$totalArr["request"]:0;
		$this->serverd = (!empty($totalArr["request"]))?$totalArr["request"]:0;;
		$this->delivered = (!empty($totalArr["imp"]))?$totalArr["imp"]:0;
		$this->clicks = (!empty($totalArr["clk"]))?$totalArr["clk"]:0;
		$this->ctr = (!empty($totalArr["imp"])&&!empty($totalArr["clk"]))?round(($totalArr["clk"]/$totalArr["imp"])*100,2):0;
		//echo "inside constructor";
	}
	
	public static function fetchAdData($adid){
		$dataStr = file_get_contents(AD_DETAIL_API.$adid);
		return $dataStr;
	}
	
	public static function getAdInfo($adData){
		$adArr = array();
		$dataArr = json_decode($adData,true);
		$adArr['name'] = $dataArr['name'];
		return $adArr;
	}
	public static function getAdEvents($adData){
		$adEventsArr = array();
		$dataArr = json_decode($adData,true);
		$data = $dataArr["adUISpec"]["adUnit"][0]["adPage"];
		foreach($data as $pagekey => $pageArr){
			foreach($pageArr as $pageEle => $pageEleArr){
				if($pageEle == "adPageElement"){
					foreach($pageEleArr as $key => $items){
						
						if(!empty($items['events'])){
							
							$adEventsArr[] = array("name"=>$items['label'],"id"=>$items['componentId']);
						}
					}
				}
			}
		}
		return $adEventsArr;
	}
	public static function parseTotalStats($arr){
		$totalArr=array();
		foreach($arr as $k=>$v){
			foreach($v as $k=>$value){
				$totalArr[$k]=$value["dcount"];
			}			
		}
		
		return $totalArr;
		
	}
	
	public static function getTotalStats($adid,$interval,$custominterval=null,$params){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["request","imp","clk"]';
		$int="&int=d";
		$queryString=$basequerystring.$evt.$params;
		//echo BASEURL.$queryString;
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		
		$totalArr=array();
		foreach($dataArr as $k=>$v){
			foreach($v as $k=>$value){
				$value = (!empty($value[0]))?$value[0]:$value;
				if(!isset($totalArr[$k]))
					$totalArr[$k] = 0;
				$totalArr[$k] += $value["dcount"];
			}
		}
		return $totalArr;
	}
	 
	 
	public static function createBaseQueryString($adid,$interval,$custominterval=null){
		$sd="";
		$ed="";
		switch($interval){
			case 'today':
				$sd="sd=".date("Y/m/d");
				$ed="&ed=".date("Y/m/d");
				break;
			case 'month':
				$sd="sd=".date("Y/m/d",strtotime("-30 days"));
				$ed="&ed=".date("Y/m/d",strtotime("-1 days"));
				break;
			case 'quarter':
				$sd="sd=".date("Y/m/d",strtotime("-90 days"));
				$ed="&ed=".date("Y/m/d",strtotime("-1 days"));
				break;
			break;
			case 'custom':
				$sd="sd=".date("Y/m/d",strtotime($custominterval['start_date']));
				$ed="&ed=".date("Y/m/d",strtotime($custominterval['end_date']));
			break;
		}
		
		$adid="&ad=".$adid;
		return $sd.$ed.$adid;
	}

		
	public static function getTotalGraphStats($adid,$interval,$custominterval=null,$params=null){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["imp","clk"]';
		if($interval == "today")
			$int="&int=h";
		else	
			$int="&int=d";
		$queryString=$basequerystring.$evt.$int.$params;
		echo BASEURL.$queryString;	
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalArr = array();
		if($interval == "today")
			$totalArr = self::getTotalGraphHourStats($dataArr);
		else if($interval == "month")	
			$totalArr = self::getTotalGraphDayStats($dataArr,date("Y/m/d",strtotime("-30 days")),date("Y/m/d",strtotime("-1 days")));
		else if($interval == "quarter")		
			$totalArr = self::getTotalGraphQuaterStats($dataArr,date("Y/m/d",strtotime("-90 days")),date("Y/m/d",strtotime("-1 days")));
		else if($interval == "custom")	
			$totalArr = self::getTotalGraphDayStats($dataArr,$custominterval['start_date'],$custominterval['end_date']);
		//self::printArr($totalArr);
		return $totalArr; 
	 }
	 
	 public static function getTotalGraphHourStats($dataArr){
		$totalArr=array();
		for($i=0;$i<24;$i++){
			$totalArr['categories'][] = $i;
			$totalArr['imp'][$i] = 0;
			$totalArr['clk'][$i] = 0;
		}
		foreach($dataArr as $k=>$v)
		{
			
			foreach($v as $k=>$value)
			{
				$value = (!empty($value[0]))?$value[0]:$value;
				foreach($value['hcount'] as $hour => $item){
					$hr = (int)$hour;
					$totalArr[$k][$hr] += $item;
				}
				
			}			

		}
		
		
		ksort($totalArr["imp"]);
		ksort($totalArr["clk"]);
		
		
		
		for($i=0;$i<24;$i++)
		{
			
			if($totalArr["imp"][$i] != 0)
				$totalArr["ctr"][$i]=round(($totalArr["clk"][$i]/$totalArr["imp"][$i])*100,2);
			else  
				$totalArr["ctr"][$i]=0;
		}
		return $totalArr;
	 }
	 public static function getTotalGraphDayStats($dataArr,$start,$end){
		$dateArr = self::createDateRangeArray($start,$end);
		$totalArr=array();
		$finalArr = array();
		
		foreach($dataArr as $date => $stats){
			foreach($stats as $key => $item){
				$item = (!empty($item[0]))?$item[0]:$item;
				if($key == "imp"){
					$totalArr[$date]['imp'] = $item['dcount'];
				}
				if($key == "clk"){
					$totalArr[$date]['clk'] = $item['dcount'];
				}
			}
		}
		
		for($i=0;$i<count($dateArr);$i++){
			
			$finalArr['categories'][] = "'".date("d M",strtotime($dateArr[$i]))."'";
			if(!empty($totalArr[$dateArr[$i]])){
				$finalArr['imp'][] = $totalArr[$dateArr[$i]]['imp'];
				$finalArr['clk'][] = (!empty($totalArr[$dateArr[$i]]['clk']))?$totalArr[$dateArr[$i]]['clk']:0;
				$finalArr['ctr'][] = ($totalArr[$dateArr[$i]]['imp'] > 0 && !empty($totalArr[$dateArr[$i]]['clk']))?round(($totalArr[$dateArr[$i]]['clk']/$totalArr[$dateArr[$i]]['imp'])*100,2):0;
			}else{
				$finalArr['imp'][] = 0;
				$finalArr['clk'][] = 0;
				$finalArr['ctr'][] = 0;
			}
		}
		
		return $finalArr;
	 }
	 public static function getTotalGraphQuaterStats($dataArr,$start,$end){
		$dateArr = self::createDateRangeArray($start,$end);
		$totalArr=array();
		$finalArr = array();
		foreach($dataArr as $date => $stats){
			foreach($stats as $key => $item){
				$item = (!empty($item[0]))?$item[0]:$item;
				if($key == "imp"){
					$totalArr[$date]['imp'] = $item['dcount'];
				}
				if($key == "clk"){
					$totalArr[$date]['clk'] = $item['dcount'];
				}
			}
		}
		
		$cnt = 0;
		$impCnt = 0;
		$clkCnt = 0;
		
		for($i=0;$i<90;$i++){
			if($cnt == 30 || $i == 89){
				if($i==89){
					$end = $dateArr[$i];
					if(!empty($totalArr[$dateArr[$i]])){
						$impCnt += $totalArr[$dateArr[$i]]['imp'];
						$clkCnt += (!empty($totalArr[$dateArr[$i]]['clk']))?$totalArr[$dateArr[$i]]['clk']:0;
					}
				}	
				$finalArr['categories'][] = "'".date("d M",strtotime($start))." - ".date("d M",strtotime($end))."'";	
				$finalArr['imp'][] = $impCnt;
				$finalArr['clk'][] = $clkCnt;
				$finalArr['ctr'][] = ($impCnt > 0)?round(($clkCnt/$impCnt)*100,2):0;;
				$cnt=0;
				$impCnt = 0;
				$clkCnt = 0;
				$start = $dateArr[$i];
			}
			if(!empty($totalArr[$dateArr[$i]])){
				$impCnt += $totalArr[$dateArr[$i]]['imp'];
				$clkCnt += (!empty($totalArr[$dateArr[$i]]['clk']))?$totalArr[$dateArr[$i]]['clk']:0;
			}	
			$end = $dateArr[$i];
			$cnt++;
		}
		return $finalArr;
	 }
	 public static function getTotalGraphCustomStats($data){
	 
	 }
	 
	 public static function getNetworkGraphStats($adid,$interval,$custominterval=null,$params)
     {
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["imp","clk"]';
		$int="&int=d";
		$gr='&gr=["nw"]';
		$queryString=$basequerystring.$evt.$int.$gr.$params;
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalArr=array();
		$impCnt = 0;
		$clkCnt = 0;
		foreach($dataArr as $k=>$v)
		{			
			foreach($v as $k=>$value)
			{
				foreach($value as $key=>$val)
				{
					if(!isset($totalArr[$val["nw"]][$k])){
						$totalArr[$val["nw"]][$k] = 0;
					}
					$totalArr[$val["nw"]][$k] += $val["dcount"];
					if($k == 'imp')
						$impCnt += $val["dcount"];
					if($k == 'clk')
						$clkCnt += $val["dcount"];	
				}				
			}	
		}
		$totalArr['impression'] = $impCnt;
		$totalArr['clicks'] = $clkCnt;
		return $totalArr;//self::printArr($networkArray);
	}
	
	public static function getEventsGraphStats($adid,$interval,$custominterval=null,$eventName){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["'.$eventName.'"]';
		$int="&int=d";
		$gr='&gr=["nw"]';
		$queryString=$basequerystring.$evt.$int.$gr;
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalArr=array();
		$totalEvt = 0;
		foreach($dataArr as $k=>$v)
		{			
			foreach($v as $kv=>$value)
			{
				foreach($value as $key=>$val)
				{
					if(!isset($totalArr[$val["nw"]])){
						$totalArr[$val["nw"]] = 0;
					}
					$totalArr[$val["nw"]] += $val["dcount"];
					$totalEvt += $val["dcount"];
					
				}				
			}	
		}
		$totalArr['totalEvents'] = $totalEvt;
		return $totalArr;//self::printArr($networkArray);
	}
	
	public static function getNetworkScreenPageStats($adid,$interval,$custominterval=null,$params)
     {
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		//$evt='&evt=["imp","clk"]';
		$int="&int=d";
		$gr='&gr=["nw"]';
		$queryString=$basequerystring.$int.$gr.$params;
		
		$dataStr=file_get_contents(PAGEVIEW_URL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalArr=array();
		$impCnt = 0;
		$clkCnt = 0;
		
		
		foreach($dataArr as $k=>$v)
		{			
			foreach($v as $k=>$value)
			{
				foreach($value as $key=>$val)
				{
					if(!isset($totalArr[$key])){
						$totalArr[$key] = 0;
					}
					$totalArr[$key] += $val["dcount"];
				}				
			}	
		}
		return $totalArr;//self::printArr($networkArray);
	}
	 
	 
	public static function getOsGraphStats($adid,$interval,$custominterval=null){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["imp","clk"]';
		$int="&int=d";
		$gr='&gr=["os"]';
		$queryString=$basequerystring.$evt.$int.$gr;
		$osArr = array();
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalImp = 0;
		$totalClk = 0;
		$osArr = array("IOS"=>array(),"Android"=>array(),"others"=>array());
		
		foreach($dataArr as $date => $events){
			foreach($events as $key => $item){
				$osValArr = $item;
				foreach($osValArr as $tempArr){
					$oskey = "others";
					$color = "#ff7a76";
					if($tempArr['os'] == "iPhone OS"){
						$oskey = "IOS";
						$color = "#33beff";
					}	
					if($tempArr['os'] == "Android"){
						$oskey = "Android";
						$color = "#ff9a39";					
					}	
					if(empty($osArr[$oskey])){
						$osArr[$oskey] = array();
						$osArr[$oskey]['clk'] = 0;
						$osArr[$oskey]['imp'] = 0;
					}
					if(!isset($osArr[$oskey][$key]))
						$osArr[$oskey][$key] = 0;
					$osArr[$oskey][$key] += $tempArr['dcount'];
					$osArr[$oskey]['color'] = $color;
					if($key == "imp")
						$totalImp += $tempArr['dcount'];
					else
						$totalClk += $tempArr['dcount'];
				}		
			}
		}
		
		//print_r($osArr);
		foreach($osArr as $key => $item){
			if(empty($osArr[$key])){
				unset($osArr[$key]);
				continue;
			}	
			if(isset($item['imp'])){
				$osArr[$key]['impPer'] = (int)(($item['imp']/$totalImp)*100);
			}
			if(isset($item['clk'])){
				$osArr[$key]['clkPer'] = (int)(($item['clk']/$totalClk)*100);
			}
			
		}
		return $osArr;
	}
	
	public static function getHourGraphStats($adid,$interval,$custominterval=null,$params=null){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["imp","clk"]';
		$int="&int=h";
		//$gr='&gr=["os"]';
		$queryString = $basequerystring.$evt.$int.$params;
		$osArr = array();
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalImp = 0;
		$totalClk = 0;
		$statsArr = array();
		for($i=0;$i<=23;$i++){
			$statsArr['imp'][$i] = 0;
			$statsArr['clk'][$i] = 0;
		}
		foreach($dataArr as $date => $events){
			foreach($events as $key => $item){
				$item = (!empty($item[0]))?$item[0]:$item;
				if($key == "imp"){
					$impArr = $item['hcount'];
					foreach($impArr as $hour => $impVal){
						$hrKey = (int)$hour;
						$statsArr['imp'][$hrKey] += $impVal;
					}
				}
				if($key == "clk"){
					$clkArr = $item['hcount'];
					foreach($clkArr as $hour => $clkVal){
						$hrKey = (int)$hour;
						$statsArr['clk'][$hrKey] += $clkVal;
					}
				}
			}
		}
		return $statsArr;
	}
	
	public static function getOsVersionsGraphStats($adid,$interval,$custominterval=null,$params=null){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["imp","clk"]';
		$int="&int=d";
		$gr='&gr=["v"]';
		$queryString = $basequerystring.$evt.$int.$gr.$params;
		$osArr = array();
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		
		$totalImp = 0;
		$totalClk = 0;
		$statsArr = array();
		$finalArr = array();
		$totalImp = 0;
		$totalClk = 0;
		$isAndroid = (strstr($params,"Android") != false)?true:false;
		$andOsNames = array("1.5"=>"Cupcake","1.6"=>"Donut","2.0"=>"Eclair","2.1"=>"Eclair","2.2"=>"Froyo","2.3"=>"Gingerbread","3.0"=>"Honeycomb","3.1"=>"Honeycomb","3.2"=>"Honeycomb","4.0"=>"Ice Cream Sandwich","4.1"=>"Jelly Bean","4.2"=>"Jelly Bean");
		foreach($dataArr as $date => $events){
			foreach($events as $key => $verArr){
				for($vCnt=0;$vCnt < count($verArr);$vCnt++){
					$vName = $verArr[$vCnt]['v'];
					if($isAndroid){
						$v = substr($verArr[$vCnt]['v'],0,3);
						$v = (!empty($andOsNames[$v]))?$andOsNames[$v]:"Android $v";
					}else{
						$v = substr($verArr[$vCnt]['v'],0,1);
					}
					if(empty($statsArr[$v])){
						$statsArr[$v]['imp'] = 0;
						$statsArr[$v]['clk'] = 0;
						$statsArr[$v]['name'] = $v;
						$statsArr[$v]['Vlists'][] = '"'.$vName.'"';
					}
					if(!in_array('"'.$vName.'"',$statsArr[$v]['Vlists'])){
						$statsArr[$v]['Vlists'][] = '"'.$vName.'"';
					}
					$statsArr[$v][$key] += $verArr[$vCnt]['dcount'];
					if($key == "imp"){
						$totalImp += $verArr[$vCnt]['dcount'];
					}
					if($key == "clk"){
						$totalClk += $verArr[$vCnt]['dcount'];
					}
				}
			}
		}
		//self::printArr($statsArr);
		usort($statsArr, "self::custom_sort");
		$lableValueArr = "";
		foreach($statsArr as $key => $item){
			$finalArr['categories'][] = "'{$item['name']}'";
			$finalArr['impression'][] = round(($item['imp']/$totalImp)*100,2);
			$finalArr['clicks'][] = (!empty($item['clk']) && $totalClk >0)?round(($item['clk']/$totalClk)*100,2):0;
			
			if($lableValueArr != "")
				$lableValueArr .= ",";
			$ctr = 	(!empty($item['imp']) && !empty($item['clk']))?round(($item['clk']/$item['imp'])*100,2):"0";
			$lableValueArr .= "'{$item['name']}':{'imp':'".number_format($item['imp'])."','clk':'".number_format($item['clk'])."','ctr':'$ctr','versions':'".implode(",",$item['Vlists'])."'}";	
		}
		$finalArr['labelDetailsArr'] = $lableValueArr;
		return $finalArr;
	}
	
	public static function getBrandsGraphStats($adid,$interval,$custominterval=null,$params=null){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["imp","clk"]';
		$int="&int=d";
		$gr='&gr=["b"]';
		$queryString = $basequerystring.$evt.$int.$gr.$params;
		$osArr = array();
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		
		$totalImp = 0;
		$totalClk = 0;
		$statsArr = array();
		$finalArr = array();
		$totalImp = 0;
		$totalClk = 0;
		foreach($dataArr as $date => $events){
			foreach($events as $key => $brandArr){
				for($vCnt=0;$vCnt < count($brandArr);$vCnt++){
					$name = $brandArr[$vCnt]['b'];
					if(empty($statsArr[$name])){
						$statsArr[$name]['imp'] = 0;
						$statsArr[$name]['clk'] = 0;
						$statsArr[$name]['name'] = $name;
					}
					$statsArr[$name][$key] += $brandArr[$vCnt]['dcount'];
					if($key == "imp"){
						$totalImp += $brandArr[$vCnt]['dcount'];
					}
					if($key == "clk"){
						$totalClk += $brandArr[$vCnt]['dcount'];
					}
				}
			}
		}
		usort($statsArr, "self::custom_sort");
		
		for($i=(count($statsArr)-1);$i>9;$i--){
			$statsArr[9]['imp'] += $statsArr[$i]['imp'];
			$statsArr[9]['clk'] += $statsArr[$i]['clk'];
			unset($statsArr[$i]);
		}
		if(!empty($statsArr[9])){
			$statsArr[9]['name'] = "Others";
		}
		
		$lableValueArr = "";
		foreach($statsArr as $key => $item){
			$finalArr['categories'][] = "'{$item['name']}'";
			$finalArr['impression'][] = round(($item['imp']/$totalImp)*100,1);
			$finalArr['clicks'][] = (!empty($item['clk']) && $totalClk >0)?round(($item['clk']/$totalClk)*100,1):0;
			if($lableValueArr != "")
				$lableValueArr .= ",";
			$ctr = 	(!empty($item['imp']) && !empty($item['clk']))?round(($item['clk']/$item['imp'])*100,2):"0";
			$lableValueArr .= "'{$item['name']}':{'imp':'".number_format($item['imp'])."','clk':'".number_format($item['clk'])."','ctr':'$ctr'}";	
		}
		$finalArr['labelDetailsArr'] = $lableValueArr;
		return $finalArr;
	}
	
	public static function getLocationGraphStats($adid,$interval,$custominterval=null,$params=null){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=["imp"]';
		$int="&int=d";
		$gr='&gr=["ctry"]';
		$queryString = $basequerystring.$evt.$int.$gr.$params;
		$osArr = array();
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalImp = 0;
		$totalClk = 0;
		$statsArr = array();
		$countryStats = array();
		foreach($dataArr as $date => $events){
			foreach($events as $key => $item){
				for($cnt=0;$cnt<count($item);$cnt++){
					if(empty($item[$cnt]['ctry']))
						continue;
					if(!isset($statsArr[$item[$cnt]['ctry']]['imp']))
						$statsArr[$item[$cnt]['ctry']]['imp'] = 0;
					$statsArr[$item[$cnt]['ctry']]['imp'] += $item[$cnt]['dcount'];
				}
			}
		}
		$countryStats[0][] = "Country";
		$countryStats[0][] = "Impressions";
		foreach($statsArr as $key => $val){
			$item = array();
			$item[] = $key;
			$item[] = $val['imp'];
			$countryStats[] = $item;
		}
		return $countryStats;
	}	
	
	public static function createDateRangeArray($start, $end) {
		$range = array();
		if (is_string($start) === true) $start = strtotime($start);
		if (is_string($end) === true ) $end = strtotime($end);

		if ($start > $end) return createDateRangeArray($end, $start);

		do {
			$range[] = date('Y-n-j', $start);
			$start = strtotime("+ 1 day", $start);
		}
		while($start <= $end);

		return $range;
	}
	
	public static function createDatesArray($days) {
 
	   //CLEAR OUTPUT FOR USE
	   $output = array();

		//SET CURRENT DATE
	   $month = date("m");
	   $day = date("d");
	   $year = date("Y");

		//LOOP THROUGH DAYS
	   for($i=1; $i<=$days; $i++){
			$output[] = date('Y-n-j',mktime(0,0,0,$month,($day-$i),$year));
	   }
	   $output = array_reverse($output);
	   //RETURN DATE ARRAY
	   return $output;

	}
	
	 public static function printArr($arr)
	 {
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	 }
	 
	 
     // Define the custom sort function
     public static function custom_sort($a,$b) {
          return $a['imp']<$b['imp'];
     }
	 
	 //New Functions for engagement
	 public static function getPageViewsStats($adid,$interval,$custominterval=null,$params=null){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$int="&int=d";
		$gr='&gr=["sv","exit","depth"]';
		$queryString=$basequerystring.$int.$gr.$params;
		$dataStr=file_get_contents(ENGMTURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalArr = array();
		
		if($interval == "today")
			$totalArr =self::getTodayStats($dataArr,$adid);
		else if($interval == "month")	
			$totalArr = self::getMonthStats($dataArr,$adid,date("Y/m/d",strtotime("-30 days")),date("Y/m/d",strtotime("-1 days")));
		else if($interval == "quarter")		
			$totalArr = self::getQuarterStats($dataArr,$adid,date("Y/m/d",strtotime("-90 days")),date("Y/m/d",strtotime("-1 days")));
		else if($interval == "custom"){
			$totalArr = self::getMonthStats($dataArr,$adid,date("Y/m/d",strtotime($custominterval['start_date'])),date("Y/m/d",strtotime($custominterval['end_date'])));
		}
			//$totalArr = self::getTotalGraphDayStats($dataArr);
		//self::printArr($totalArr);*/
		return $totalArr; 
	}
		 
		 
	public static function getTodayStats($dataArr,$adid){
		$totalArr = self::getSvExitDepth($dataArr,$adid);
		for($i=0;$i<24;$i++)
		$totalArr['categories'][] = $i;				
		$totalArr['pageviews']=self::getPageViewsHourStats($adid,"today","");			
		return $totalArr;
	}
		 
	public static function getMonthStats($dataArr,$adid,$start,$end){
		$totalArr = self::getSvExitDepth($dataArr,$adid);
		
		$dateArr = self::createDateRangeArray($start,$end);
		$temppageViewArr=$totalArr['pageviews'];
		$finalpageViewArr=array();
		for($i=0;$i<count($dateArr);$i++){
			
			$totalArr['categories'][] = "'".date("d M",strtotime($dateArr[$i]))."'";
			if(!empty($temppageViewArr[$dateArr[$i]])){
				$finalpageViewArr[$i]=$temppageViewArr[$dateArr[$i]];
			}else{
				$finalpageViewArr[$i] = 0;
			}
		}

		$totalArr['pageviews']=	$finalpageViewArr;	
		return $totalArr;
	}
		 
	public static function getQuarterStats($dataArr,$adid,$start,$end){
		$totalArr = self::getSvExitDepth($dataArr,$adid);
		$dateArr = self::createDateRangeArray($start,$end);
		$temppageViewArr=$totalArr['pageviews'];
		$finalpageViewArr=array();
		$totalArr['pageviews']=array();
		$cnt = 0;
		$pageViewCnt = 0;
		for($i=0;$i<90;$i++){
			if($cnt == 30 || $i == 89){
				if($i==89){
				$end = $dateArr[$i];
					if(!empty($temppageViewArr[$dateArr[$i]])){
						$pageViewCnt += $temppageViewArr[$dateArr[$i]];
					}	
				}
				$totalArr['categories'][] = "'".date("d M",strtotime($start))." - ".date("d M",strtotime($end))."'";	
				$totalArr['pageviews'][]=$pageViewCnt;
				$cnt=0;
				$pageViewCnt = 0;
				$start = $dateArr[$i];
			}
			if(!empty($temppageViewArr[$dateArr[$i]])){
				$pageViewCnt += $temppageViewArr[$dateArr[$i]];
			}	
			$end = $dateArr[$i];
			$cnt++;
		}
		
		//$totalArr['pageviews']=	$finalpageViewArr;	
		return $totalArr;
	}
	public static function getSvExitDepth($dataArr,$adid){
		$totalArr=array();
		$totalArr['sv_total']=0;
		$totalArr['exit_total']=0;
		$totalArr['depth_total']=0;
		$totalArr['totalpageviews']=0;
		foreach($dataArr as $k=>$v){	
			if(isset($v['sv']['total'])){
				$totalArr['pageviews'][$k]=$v['sv']['total']['dcount'];
				unset($v['sv']['total']);
			}
			
			foreach($v  as $gr => $item){
				foreach($item as $key=>$value){			
					if(!empty($key)){
						if(!empty($value['dcount'])){
							$totalArr[$gr][$key]=(!empty($totalArr[$gr][$key]))?$totalArr[$gr][$key]:0+$value['dcount'];
							//$totalArr[$gr."_keys"][]=$key;
							$totalArr[$gr.'_total']=$totalArr[$gr.'_total']+$value['dcount'];
						}						
					}
				}
			}						
		}			
		$totalArr['totalpageviews']=$totalArr['sv_total'];
		$allIds= array_unique(array_merge(array_keys($totalArr['sv']),array_keys($totalArr['exit'])));
		
		$i=0;
		foreach ($allIds as $comp){
			$totalArr['exit'][$comp]=(key_exists($comp,$totalArr['exit']))?$totalArr['exit'][$comp]:0;
			$totalArr['sv'][$comp]=(key_exists($comp,$totalArr['sv']))?$totalArr['sv'][$comp]:0;
		}
		ksort($totalArr['exit']);
		ksort($totalArr['sv']);
		
		$totalArr['componentids']=array_keys($totalArr['sv']);
		
		ksort($totalArr['depth']);
		$totalArr['depth_pages']=array_map(function ($val){return "'". $val. " Pages'";},array_keys($totalArr['depth']));
		
		return $totalArr;
	}
		 
	public static function getPageViewsHourStats($adid,$interval,$custominterval=null){
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		
		$int="&int=h";
		$gr='&gr=["sv"]';
		
		$queryString=$basequerystring.$int.$gr;
		$dataStr=file_get_contents(ENGMTURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalArr=array();
		
		for($i=0;$i<24;$i++)
			$totalArr[$i] = 0;
			
		foreach($dataArr as $k=>$v){	
			$value = (isset($v['sv']['total']))?$v['sv']['total']:null;// = (!empty($value[0]))?$value[0]:$value['total'];
				
			if($value!=null){
				foreach($value['hcount'] as $hour => $item){
					$hr = (int)self::isEmpty($hour);
					$totalArr[$hr] += self::isEmpty($item);
				}
			}
						
		}		
		if(!empty($totalArr))
		ksort($totalArr);

		return $totalArr;
	}
		 
		 
	public static  function isEmpty($val,$defval=0){
			return !empty($val)?$val:$defval;
	}
		 
	public static function getAdDetailsByAdId($adid){
		$component=array();
		$url=AD_DETAIL_API.$adid;
		$dataStr=file_get_contents($url);
		$dataArr=json_decode($dataStr,true);
		return $dataArr;
	}
		
	public static function getComponentArrByAdId($adid,$adDetails){
		$adDetails = json_decode($adDetails,true);
		foreach($adDetails['adUISpec']['adUnit'] as $k=>$v){
			foreach ($v['adPage'] as $index => $page){
				$component[$page['componentId']]="'".self::isEmpty($page['name'],' ')."'";
			}
		}
		return $component;	
	}
	public static function getAllEventsStats($adid,$interval,$custominterval=null,$eventArr){
		$eventName = "";
		$events = array();
		for($i=0;$i<count($eventArr);$i++){
			if($eventName != "")
				$eventName .= ","; 
			$eventName .= '"'.$eventArr[$i]['id'].'"'; 	
			$events[$eventArr[$i]['id']] = $eventArr[$i]['name'];
		}
		$basequerystring=self::createBaseQueryString($adid,$interval,$custominterval);
		$evt='&evt=['.$eventName.']';
		$int="&int=d";
		$gr='';
		$queryString=$basequerystring.$evt.$int.$gr;
		
		$dataStr=file_get_contents(BASEURL.$queryString);
		$dataArr=json_decode($dataStr,true);
		$totalArr=array();
		$totalEvt = 0;
		foreach($dataArr as $k=>$v){	
			foreach($v as $key => $value){
				if(empty($totalArr[$key]))
					$totalArr[$key] = 0;
				$totalArr[$key] += $value["dcount"];
				$totalEvt += $value["dcount"];
			}
		}
		$finalArr['total'] = $totalEvt;
		$lableValueArr = "";
		foreach($totalArr as $key => $item){
			$finalArr['categories'][]	= "'".$events[$key]."'";
			$finalArr['eventsVal'][]	= $item;
			$finalArr['eventsPc'][]	= ($totalEvt >0)?round(($item/$totalEvt)*100,2):0;
			if($lableValueArr != "")
				$lableValueArr .= ",";
			$lableValueArr .= "'{$events[$key]}':'".number_format($item)."'";	
		}
		$finalArr['labelArr'] = $lableValueArr;
		//$totalArr['totalEvents'] = $totalEvt;
		return $finalArr;//self::printArr($networkArray);
	}
	
	public static function getComponentNameArrById($componentidArr,$componentArr){
		$compIdArr=array();
		foreach($componentidArr as $comp){
			if(key_exists($comp,$componentArr)){
				$compIdArr[]= $componentArr[$comp];
			}
		}
		return $compIdArr;
	 }
		 
	public static function  getMax($arr)
	{
		$i=0;
		foreach($arr as $k=>$val){
			if($i==0){
				$max=$val;
				$key=$k;
			}
			$i++;
			if($val>$max){
				$max=$val;
				$key=$k;
			}
		}
		return $key;
	}
} 
?>