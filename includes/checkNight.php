<?php
date_default_timezone_set("Europe/London");

function checkNight()
{
    $schedule = array
	( //  00 01 02 03 04 05 06 07 08 09 10 11 12 13 14 15 16 17 18 19 20 21 22 23  hours **** DOES SPEAK IF 1 ****
	array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1 ,1 ,1, 1, 1, 1, 1, 1, 1, 1, 0, 0), // Sunday
	array(0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0), // Monday
	array(0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0), // Tuesday
	array(0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0), // Wednesday
	array(0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0), // Thursday
	array(0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0), // Friday
	array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1 ,1 ,1, 1, 1, 1, 1, 1, 1, 1, 0, 0), // Saturday
        );
    
    $day = (int)date('w');
    $hour = (int)date('G');
    
    if(!$schedule[$day][$hour])
    {
        return true;
    }
}