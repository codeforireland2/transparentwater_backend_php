<?
date_default_timezone_set('Europe/Dublin');
ini_set('display_errors', '1');
ini_set('allow_url_fopen', '1');
Header("Access-Control-Allow-Origin: *");
Header("Content-type:application/json; charset=UTF-8");

$date=date("YmdH");
if(file_exists("current.json")){
	$contents=file_get_contents("current.json");
	$json=json_decode($contents,true);
	$version=$json['version'];
	if($version==$date){
		echo $contents;
	}
	else{
		$url="https://www.water.ie/site-files/cms-templates/utils/proxy/index.xml?https://services2.arcgis.com/OqejhVam51LdtxGa/ArcGIS/rest/services/WaterAdvisoryCR021/FeatureServer/0/query?returnGeometry=true&where=STATUS!%3D%27Closed%27&outFields=*&orderByFields=STARTDATE%20DESC&outSR=4326&returnIdsOnly=false&f=pgeojson";
		$contents=file_get_contents($url);
		echo $contents;
		$contents=preg_replace('/"crs"/','"version":"'.$date.'","crs"',$contents);
		file_put_contents("current.json",$contents,LOCK_EX);
	}
}
else{
	$url="https://www.water.ie/site-files/cms-templates/utils/proxy/index.xml?https://services2.arcgis.com/OqejhVam51LdtxGa/ArcGIS/rest/services/WaterAdvisoryCR021/FeatureServer/0/query?returnGeometry=true&where=STATUS!%3D%27Closed%27&outFields=*&orderByFields=STARTDATE%20DESC&outSR=4326&returnIdsOnly=false&f=pgeojson";
	$contents=file_get_contents($url);
	echo $contents;
	$contents=preg_replace('/"crs"/','"version":"'.$date.'","crs"',$contents);
	file_put_contents("current.json",$contents,LOCK_EX);
}
