<?php

require_once '/home/joshwalls/JarvisRT/includes/say.php';


date_default_timezone_set("Europe/London");
say("Ok, I'll do a speed test. This may take a while.");

$output = shell_exec("speedtest-cli");

$pos = strpos($output, "Download: ") + 10;

$down = substr($output, $pos, 5);

$pos = strpos($output, "Upload: ") + 8;

$up = substr($output, $pos, 5);

say("The download speed is $down megabits per second and the upload speed is $up megabits per second.");