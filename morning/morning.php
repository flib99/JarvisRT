<?php
require_once '/home/joshwalls/JarvisRT/includes/say.php';

date_default_timezone_set("Europe/London");

$hour = date("G");
$minute = date("i");

$day = date("l");
$date = date("j");
$month = date("F");
$year = date("Y");
$suffix;


$showYesterday = [];
$episodeYesterday = [];
$seasonYesterday = [];
$nameYesterday = [];
$descriptionYesterday = [];
$addedYesterday = "";

global $airingToday;



$aringTodayJSON = file_get_contents("http://10.0.1.13:8081/api/60a2cf57f71ee06eab61c5d8174d972d/?cmd=future&sort=date&type=today");
$todayDecoded = json_decode($aringTodayJSON);
                    
$show = $todayDecoded->data->today;

$showToday = [];
$seasonToday = [];
$episodeToday = [];
$nameToday = [];
$descriptionToday = [];


if ($show[0] != "")
{
    foreach($show as $post)
    {
		array_push($showToday, strval($post->show_name));
		array_push($seasonToday, strval($post->season));
		array_push($episodeToday, strval($post->episode));
		array_push($nameToday, strval($post->ep_name));
		array_push($descriptionToday, strval($post->ep_plot));
    }
}
else
{
	$airingToday = "There is nothing airing today.";
}

for($i = 0; $i <= count($showToday) - 1; $i++)
{
	if ($i == 0)
	{
		$airingToday = "Airing today on TV are " . $airingToday . $showToday[$i] . " season " . $seasonToday[$i] . " episode " . $episodeToday[$i] . ", " . $nameToday[$i] . ", with the description: " . $descriptionToday[$i];
	}
	else
	{
		$airingToday = $airingToday . ", and " .  $showToday[$i] . " season " . $seasonToday[$i] . " episode " . $episodeToday[$i] . ", " . $nameToday[$i] . ", with the description: " . $descriptionToday[$i];
	}
}

$string = file_get_contents("http://10.0.1.13:32400/library/sections/16/recentlyAdded?X-Plex-Token=HQPQhdSErtnRjuZKxzzN");


if ($string == "")
{
	$string = file_get_contents("http://10.0.1.13:32400/library/sections/16/recentlyAdded?X-Plex-Token=HQPQhdSErtnRjuZKxzzN");
}

if ($string == "")
{
	$string = file_get_contents("http://10.0.1.13:32400/library/sections/16/recentlyAdded?X-Plex-Token=HQPQhdSErtnRjuZKxzzN");
}

if ($string == "")
{
	$string = file_get_contents("http://10.0.1.13:32400/library/sections/16/recentlyAdded?X-Plex-Token=HQPQhdSErtnRjuZKxzzN");
}


$xml2 = simplexml_load_string($string);


$yesterday = strval(intval(date("d"))-1);
if (strlen($yesterday) == 1)
{
	$yesterday = "0". $yesterday;
}
$yesterday = date("Y-m-") . $yesterday;

$count= 0;

if ($xml2['size'] != "0")
{
    for($i = 0; $i < 15; $i++)
    {
    	//if ($xml2->Video[$i]['originallyAvailableAt'] == $yesterday)
    	if ($xml2->Video[$i]['originallyAvailableAt'] == $yesterday)
    	{
        	array_push($showYesterday, strval($xml2->Video[$i]['grandparentTitle']) );
        	array_push($seasonYesterday, strval($xml2->Video[$i]['parentIndex']));
        	array_push($episodeYesterday, strval($xml2->Video[$i]['index']));
        	array_push($nameYesterday, strval($xml2->Video[$i]['title']));
        	array_push($descriptionYesterday, strval($xml2->Video[$i]['summary']));
        	$count++;
        	
        }
    }
}
else
{
	$addedYesterday = "There is no new TV.";
}

if ($count == 0)
{
	$addedYesterday = "There is no new TV.";
}


for($i = 0; $i <= count($showYesterday) - 1; $i++)
{
	if ($i == 0)
	{
		$addedYesterday = "Added to Plex yesterday are " . $addedYesterday . $showYesterday[$i] . " season " . $seasonYesterday[$i] . " episode " . $episodeYesterday[$i] . ", " . $nameYesterday[$i] . ", with the description: " . $descriptionYesterday[$i];
	}
	else
	{
		$addedYesterday = $addedYesterday . ", and " . $showYesterday[$i] . " season " . $seasonYesterday[$i] . " episode " . $episodeYesterday[$i] . ", " . $nameYesterday[$i] . ", with the description: " . $descriptionYesterday[$i];
	}
}


if (substr($date, -1) == 1)
{
    $suffix = "st";
}
else if (substr($date, -1) == 2)
{
    $suffix = "nd";
}
else if (substr($date, -1) == 3)
{
    $suffix = "rd";
}
else
{
    $suffix = "th";
}



$xml = simplexml_load_file("http://api.worldweatheronline.com/free/v2/weather.ashx?key=438407605602fac1bca4c6c8bab45&q=HP22&date=today&=num_of_days=1&format=xml");
$currentTemp = $xml->current_condition->temp_C;
$maxTemp = $xml->weather->maxtempC;
$minTemp = $xml->weather->mintempC;
$feelLikeTemp = $xml->current_condition->FeelsLikeC;
$currentConditions = $xml->current_condition->weatherDesc;

$currentDataJSON = file_get_contents("http://10.0.1.13/api/data/currentdata.php");
$currentDecoded = json_decode($currentDataJSON);

$insideTemp = $currentDecoded->current->temp;
$insideHumidity = $currentDecoded->current->humidity;

echo (date("H:i:s", time()) . ": ");

shell_exec("aplay /home/joshwalls/JarvisRT/morning/alarm.wav");

say("Good morning Josh. It is " . intval(date('i')) . " minutes past " . date("g") . " on " . $day . " the " . $date . $suffix . " of " . $month . " " . $year . ". The current temperature is " . $currentTemp . " degrees Celcius but it feels like " . $feelLikeTemp . ". The conditions are " . rtrim(strtolower($currentConditions)) . ". Today's high temperature is " . $maxTemp . " degrees and low is " . $minTemp . " degrees. Inside currently it is " . $insideTemp . " degrees and the humidity is " . $insideHumidity . " percent. " . $airingToday . " " . $addedYesterday . " It is " . date("g i") . ". You have " . strval(712 - intval(date("gi"))) . " minutes left. That is all.");
