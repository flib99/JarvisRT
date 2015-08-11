<?php

function say($message)
{
    echo (date("H:i:s", time()) . ": " . $message . "\n");
    exec ("/usr/local/bin/simple_google_tts en " . escapeshellarg($message));
    //exec ("say " . escapeshellarg($message));
}

date_default_timezone_set("Europe/London");

shell_exec("aplay /home/joshwalls/JarvisRT/morning/alarm.wav");

if (date('g') < 7)
{
	say("Good morning. It is " . date("g:i") . ". You have " . strval(720 - intval(date("gi")+40)) . " minutes left.");
}
else
{
	say("Good morning. It is " . date("g:i") . ". You have " . strval(720 - intval(date("gi"))) . " minutes left.");
}