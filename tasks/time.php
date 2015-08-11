<?php

date_default_timezone_set("Europe/London");

require_once '/home/joshwalls/JarvisRT/includes/say.php';

if  (date('i') == 15)
{
	say("It is quarter past " . date('g') . '.');
}
else if  (date('i') == 30)
{
	say("It is half past " . date('g') . '.');
}
else if  (date('i') == 45)
{
	say("It is quarter to " . (date('g') + 1) . '.');
}
else if (date('i') == 0)
{
	say("It is " . date('g') . " o'clock.");
}
else
{
	say("It is " . date("g:i") . ".");
}