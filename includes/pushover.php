<?php

function pushover($message, $title, $priority)
{
    echo date("H:i:s", time()) . ": ";
    curl_setopt_array($ch = curl_init(), array(
        CURLOPT_URL => "https://api.pushover.net/1/messages.json",
        CURLOPT_POSTFIELDS => array(
            "token" => "aDJpXkt11SxNZ5aSq1b8QNxsXFo8ru",
            "user" => "fGKCKasiY1GkBPxLH3dijCyvdBsS42",
            "message" => $message,
            "title" => $title,
            "priority" => $priority,
        ),
        CURLOPT_SAFE_UPLOAD => true,
    ));
    curl_exec($ch);
    curl_close($ch);
    echo ("\n");
}