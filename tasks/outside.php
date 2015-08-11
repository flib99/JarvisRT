<?php

require_once '/home/joshwalls/JarvisRT/includes/say.php';

date_default_timezone_set("Europe/London");
$xml = simplexml_load_file("http://api.worldweatheronline.com/free/v2/weather.ashx?key=438407605602fac1bca4c6c8bab45&q=HP22&date=today&=num_of_days=1&format=xml");
$currentTemp = $xml->current_condition->temp_C;
$maxTemp = $xml->weather->maxtempC;
$minTemp = $xml->weather->mintempC;
$feelLikeTemp = $xml->current_condition->FeelsLikeC;
$currentConditions = $xml->current_condition->weatherDesc;
$time = $xml->current_condition->observation_time;

say("At $time GMT; the temperature is " . $currentTemp . " degrees Celcius but it feels like " . $feelLikeTemp . ". The conditions are " . rtrim(strtolower($currentConditions)) . ". Today's high temperature is " . $maxTemp . " degrees and low is " . $minTemp . " degrees.");