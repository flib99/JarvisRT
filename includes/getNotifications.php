<?php
require("const.php");
require_once("say.php");
require_once("cleaner.php");

function getNotifications()
{
	
	
	$curl = curl_init();
	
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'https://api.pushover.net/1/messages.json?secret=' . SECRET . '&device_id=' . ID
	));
	
	$notifications = json_decode(curl_exec($curl));
	
	$message = [];
	
	curl_close($curl);
	
	for($i = 0; $i <= count($notifications->messages); $i++)
        {
            if (substr($notifications->messages[$i]->title,0,5) == "IFTTT")
            {
                $message[$i] = substr($notifications->messages[$i]->title, strpos($notifications->messages[$i]->title, ' ')+1);
            }
            else
            {
                $message[$i] = $notifications->messages[$i]->title;
            }
        }
	
	
	
	
        
        if (count($notifications->messages) > 1)
        {
	$say = "Excuse me, you have ";
	$say .= count($notifications->messages);
 	$say .= " new notifications. ";
	
	for($i = 1; $i <= count($notifications->messages); $i++)
	{
		$say .= ordinal($i) . " notification from " . $notifications->messages[$i-1]->app . ": " . $message[$i-1] . " , " . $notifications->messages[$i-1]->message . ". ";
	}
	
        }
        elseif (count($notifications->messages) == 1)
        {
                $say = "Excuse me, you have a new notification from " . $notifications->messages[0]->app . ": " . $message[0] . " , " . cleaner($notifications->messages[0]->message) . ". ";
        }
        else
        {
                return FALSE;
        }
        
        say($say);
        
        $lastID = end($notifications->messages)->id;
	
	$curl = curl_init();
	
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => "https://api.pushover.net/1/devices/" . ID . "/update_highest_message.json",
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => array(
			secret => SECRET,
			message => $lastID
		)
	));
	
	curl_exec($curl);
	
	curl_close($curl);
}

