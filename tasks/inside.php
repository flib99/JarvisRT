<?php

date_default_timezone_set("Europe/London");

require_once '/home/joshwalls/JarvisRT/includes/say.php';

$currentDataJSON = file_get_contents("http://10.0.1.13/api/data/currentdata.php");
$currentDecoded = json_decode($currentDataJSON);

$insideTemp = $currentDecoded->current->temp;
$insideHumidity = $currentDecoded->current->humidity;
$insideLight = $currentDecoded->current->light;

say("Inside currently it is $insideTemp degrees Celcius, the humidity is $insideHumidity percent and the light level is $insideLight percent.");
