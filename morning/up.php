<?php

function say($message)
{
    echo (date("H:i:s", time()) . ": " . $message . "\n");
    exec ("/usr/local/bin/simple_google_tts en " . escapeshellarg($message));
    //exec ("say " . escapeshellarg($message));
}

date_default_timezone_set("Europe/London");

say("It is time to get up now.");
