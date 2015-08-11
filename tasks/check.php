<?php

include_once '/home/joshwalls/JarvisRT/includes/say.php';

function secondsToTime($seconds) {
    $s = $seconds%60;
    $m = floor(($seconds%3600)/60);
    $h = floor(($seconds%86400)/3600);
    $d = floor(($seconds%2592000)/86400);
    $M = floor($seconds/2592000);
     
    if ($m == 0)
    {
        $human = "$s seconds";
    }
    else if ($h == 0)
    {
        $human = "$m minutes and $s seconds";
    }
    else if ($d == 0)
    {
        $human = "$h hours, $m minutes and $s seconds";
    }
    else if($M == 0)
    {
        $human = "$d days, $h hours, $m minutes and $s seconds";
    }
    
    return $human;
    
}

function ping($host,$port=80,$timeout=6)
{
    $fsock = fsockopen($host, $port, $errno, $errstr, $timeout);
    if ( ! $fsock )
    {
        return FALSE;
    }
    else
    {
        return TRUE;
    }
}

function isRunning($bool)
{
    if ($bool)
    {
        return " running";
    }
    else
    {
        return " not running";
    }
}

date_default_timezone_set("Europe/London");

exec("ps aux | grep 'jarvisrt' | grep -v grep | awk '{ print $2 }' | head -1", $pidArray);

$pid = $pidArray[0];

exec("ps aux | grep 'jarvisrt' | grep -v grep | awk '{ print $9 }' | head -1", $timeArray);

$uptime = strtotime($timeArray[0]);

$uptime = secondsToTime(time() - $uptime);

$monitor = json_decode(file_get_contents("https://api.uptimerobot.com/getMonitors?apiKey=u237316-a659ecff3b1480f171a9b85c&format=json&noJsonCallback=1"));

for($i = 0; $i < count($monitor->monitors->monitor); $i++)
{
    if ($monitor->monitors->monitor[$i]->status == "2")
    {
        $up = "up";
    }
    else
    {
        $up = "down";
    }
    
    $status .= $monitor->monitors->monitor[$i]->friendlyname . " is " . $up . ', ';
}

say("I am still working at " . date("g:i a") . ". Running with PID of $pid and an uptime of $uptime. Plex is " . isRunning(shell_exec("ps aux | grep -i 'plexmediaserver' | grep -v grep")) . ", SickRage is " . isRunning(shell_exec("ps aux | grep -i 'sickrage' | grep -v grep")) . ", Couchpotato is " . isRunning(shell_exec("ps aux | grep -i 'couchpotato' | grep -v grep")) . " and u Torrent is " . isRunning(shell_exec("ps aux | grep -i 'utorrent' | grep -v grep")) . ". The local server is " . isRunning(ping("server.local")) . " and the remote server is " . isRunning(ping("joshwalls.co.uk")) . ". " . $status);

