<?php

function switching($socket, $status)
{
    $random = rand(1,5);
    if($random ==1)
    {
        say("Ok. I'll turn the " . $socket . " " . $status . ".");
    }
    else if ($random == 2)
    {
        say("The " . $socket . " are being switched " . $status . ".");
    }
    else if ($random == 3)
    {
        say("Right-O, I'll turn the " . $socket . " " . $status . ".");
    }
    else if ($random == 4)
    {
        say("Standby. I'm switching the " . $socket . " " . $status . ".");
    }
    else if ($random == 5)
    {
        say("Ok. I'm switching the " . $socket . " " . $status . ".");
    }
    
}