<?php

ob_start("ob_gzhandler");

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title> Transparent Water </title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="author" content="NKM,ibis" />
	<meta name="keywords" content="" />
	<meta name="description" content="Code for Ireland's Transparent Water app lets you see water works in your area." />
	<meta name="theme-color" content="#6666FF" />
	<link rel="icon" type="image/png" href="favicon.ico.png">    
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='manifest' href='manifest.json'>
    <meta name='mobile-web-app-capable' content='yes'>
    <meta name='apple-mobile-web-app-capable' content='yes'>
	<style>
		body,td,p,input,textarea{font:10pt Verdana}
		.nav { height: 8vh; }
		.navtile{height:8vh;float:left;padding-left:20px;padding-right:20px}
		#mapid { height: 90vh; }
		#loader { width: 540px;height: 240px; }
	</style>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
	  integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
	  crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
	  integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
	  crossorigin=""></script>
	  <script src="js/multirange.js"></script>
 	<link rel="stylesheet" href="css/multirange.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<?
$Y=date("Y");
$m=date("m");
$M=date("M");
$d=date("d");
$datestr="dates=[];";
$datestr2="dates2=[];";
for($a=0;$a<=50;$a++){
	$date=date("D j M",mktime(0,0,0,$m,($d+$a),$Y));
	$date2=date("U",mktime(0,0,0,$m,($d+$a),$Y));
	if($a==0){$start=$date;}
	if($a==50){$end=$date;}
	$datestr.="\ndates[$a]='$date';";
	$datestr2.="\ndates2[$a]=$date2"."000".";";
}
?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	if (navigator.serviceWorker.controller) {
		console.log('[PWA Builder] active service worker found, no need to register')
	} 
	else {
		//*Register the ServiceWorker
		navigator.serviceWorker.register('/codeforireland2/sw.js', {scope: './'
		}).then(function(reg) {
			console.log('Service worker has been registered for scope:'+ reg.scope);
		});
	}
	//**get the user's location if available 
	function getLocation() {
	  if (navigator.geolocation) {
		  //alert("Trying");
		navigator.geolocation.getCurrentPosition(showPosition,onPosError);
	  } else {
		//x.innerHTML = "Geolocation is not supported by this browser.";
	  }
	}

	<?echo $datestr;?>

	<?echo $datestr2;?>

	function showDate(){
		val=document.getElementById('dslider').value.split(",");
		start=dates[Math.round(val[0]/2)];
		end=dates[Math.round(val[1]/2)];
		document.getElementById('drange').innerHTML=start+" - "+end;
		start=dates2[Math.round(val[0]/2)];
		end=dates2[Math.round(val[1]/2)];
		doDateRange(start,end);
	}
	

	function showPosition(position) {
		//alert("showPosition");
		var lat = document.getElementById("lat");
		var lng = document.getElementById("long");
		lat.value = position.coords.latitude;
		lng.value = position.coords.longitude;
	}
	function onPosError(error){
		alert('Error occurred. Error code: ' + error.code);;
	}
	//-->
	</SCRIPT>
</head>

<body onload="getData()">
<div class="container">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" style="color:white" href="#"><IMG SRC="wicon.png" WIDTH="36" HEIGHT="36" BORDER="0" ALT=""> Transparent Water </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav">
	<div style="color:white;padding-top:8px">
		Date Range:
		<input type="range" id="dslider" multiple value="0,100" onchange="showDate()"/>
		<span id="drange"><?echo "$start - $end";?></span>
	</div>
      <a class="nav-item nav-link" href="contact.html">Contact</a>
      <a class="nav-item nav-link disabled" href="#">Disabled</a>
    </div>
  </div>
</nav>
<div id="mapid"></div>

<SCRIPT LANGUAGE="JavaScript">
<!--
	var mymap = L.map('mapid').setView([53.3498, -6.2603], 9);
	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoiaWJpcyIsImEiOiJjanJnNnc4ZXMxMjl6NDRwOGU5cnFyNTdjIn0.kDcWA40RF18x99tMlx9UQA'
}).addTo(mymap);
var popup = L.popup()
    .setLatLng([53.1498, -6.2603])
    .setContent('<IMG SRC="loadinfo.gif" WIDTH="200" HEIGHT="200" BORDER="0" ALT="">')
    .openOn(mymap);
//var marker = L.marker([53.3498, -6.2603]).addTo(mymap);
//marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();

var data;
//**calls the system generator
async function getData(){
	console.log("makeMap");
	let response = await fetch('https://www.pizzahutdelivery.ie/z19ghjklpom/fetch.php');
	let resulttext = await response.text();
	//console.log(resulttext);
	if(resulttext.indexOf('FeatureCollection')>-1){
		//alert("Got the data");
		data=JSON.parse(resulttext);
		console.log(data);
		loadMap();
	}
	else{
		console.log(resulttext);
	}
}
var redIcon = new L.Icon({
  iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});
var orangeIcon = new L.Icon({
  iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});
var greenIcon = new L.Icon({
  iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});
markers=[];
function loadMap(){
	mymap.removeLayer(popup);
	for(let i=0;i<data.features.length;i++){
		//console.log(data.features[i].geometry.coordinates[1]+","+data.features[i].geometry.coordinates[0]);
		lat=data.features[i].geometry.coordinates[1];
		lon=data.features[i].geometry.coordinates[0];
		loc=data.features[i].properties.LOCATION;
		desc=data.features[i].properties.DESCRIPTION;
		boil=data.features[i].properties.BOILWATERNOTICE;
		title=data.features[i].properties.TITLE;
		if(title.indexOf("Do Not Consume")>-1){markers[i]=L.marker([lat,lon], {icon: redIcon}).addTo(mymap);}
		else if(boil==1){markers[i]=L.marker([lat,lon], {icon: orangeIcon}).addTo(mymap);}
		else if(desc.indexOf("Works are now complete")>-1){markers[i]=L.marker([lat,lon], {icon: greenIcon}).addTo(mymap);}
		else{markers[i] = L.marker([lat, lon]).addTo(mymap);}
		markers[i].bindPopup("<b>"+loc+"</b><br><div style=\"max-height:75vh;overflow:auto\">"+desc+"</div>");
	}
}
function clearMap(){
	for(let i=0;i<markers.length;i++){
		mymap.removeLayer(markers[i]);
	}
}
function clearSearch(){
	for(let i=0;i<markers.length;i++){
		markers[i].setOpacity(1);
	}
}
function doSearch(){
	search=document.getElementById('search').value;
	for(let i=0;i<data.features.length;i++){
		text=data.features[i].properties.LOCATION+" "+data.features[i].properties.DESCRIPTION;
		if(text.indexOf(search)>-1){
			markers[i].setOpacity(1.0);
		}
		else{
			markers[i].setOpacity(0.0);
		}
	}
}
function doDateRange(start,end){
	console.log(start+","+end);
	for(let i=0;i<data.features.length;i++){
		t=data.features[i].properties.STARTDATE*1;
		t2=data.features[i].properties.ENDDATE*1;
		boil=data.features[i].properties.BOILWATERNOTICE;
		title=data.features[i].properties.TITLE;
		if((t>=start && t<=end)|(t2>=start && t2<=end)){
			markers[i].setOpacity(1.0);
		}
		else if(boil==1){
			markers[i].setOpacity(1.0);
		}
		else if(title.indexOf("Do Not Consume")>-1){
			markers[i].setOpacity(1.0);
		}
		else{
			markers[i].setOpacity(0.0);
		}
	}
}

//-->
</SCRIPT>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <a class="navbar-brand" style="color:white" href="#">&copy; RecycleThis 2018</a>
</nav>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
