<?php

require 'chatterbotapi.php';
require 'say.php';

function ai($input)
{
    $factory = new ChatterBotFactory();
    
    $random = rand(1, 2);
    if ($random == 1)
    {
        $bot1 = $factory->create(ChatterBotType::PANDORABOTS, 'b0dafd24ee35a477');
        echo 'Pandora';
    }
    else
    {
        $bot1 = $factory->create(ChatterBotType::CLEVERBOT);
        echo 'Clever';
    }
    
    $ai = $bot1->createSession();
    
    $condition = true;
    
    $handle = fopen ("php://stdin","r");
    $endings = ["exit", "goodbye", "good bye", "quit", "go away", "bye", "i'll see you later"];

    while($condition)
    {
        $response = $ai->think($input);
        $response = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $response);
        say($response);
        $input = fgets($handle);
        foreach ($endings as $i)
        {
            if (stripos($input, $i) !== FALSE)
            {
                echo "Ending";
                $condition = false;
                return;
            }
        }
        
    }
}

ai("Hello");