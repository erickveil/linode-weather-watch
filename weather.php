<?php
/**
 * Created by PhpStorm.
 * User: eveil
 * Date: 6/6/14
 * Time: 2:41 PM
 */
date_default_timezone_set("America/Los_Angeles");
$sac_weather="http://weather.yahooapis.com/forecastrss?w=2486340";

$resource=curl_init();
curl_setopt($resource,CURLOPT_URL,$sac_weather);
curl_setopt($resource,CURLOPT_RETURNTRANSFER,1);
if(!$data=curl_exec($resource)){
    throw new Exception(curl_error($resource));
}

curl_close($resource);

if(!$dat_obj=simplexml_load_string($data)){
    print_r($data);
    throw new Exception("failed to load data to xml object");
}

$today=date("D");

$path_general="/rss/channel/item/yweather:forecast[@day='${today}']";

$results=$dat_obj->xpath($path_general);

$today_forecast=$results[0];

$fordate=$today_forecast->attributes()->date;
$forlow=$today_forecast->attributes()->low;
$forhi=$today_forecast->attributes()->high;
$forgen=$today_forecast->attributes()->text;

$msg="Today will be ${forgen}. Temperature is ${forlow} to ${forhi}. (${fordate})";

if ($argc==1){

    echo $msg."\n";
}
else if ($argc>1){
    mail($argv[1],"",$msg);
}
else{
    throw new Exception("Output failure.");
}



