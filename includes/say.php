<?php
require("checkNight.php");

date_default_timezone_set("Europe/London");

function say($message)
{
    echo (date("H:i:s", time()) . ": " . $message . "\n");

   if (!checkNight())
    {
    	//exec("say " . escapeshellarg($message));
        //exec ("/usr/local/bin/simple_google_tts -p en " . escapeshellarg($message));
        //exec("espeak " . escapeshellarg($message)); 
        
//        $response = json_decode(file_get_contents("https://www.yakitome.com/api/rest/tts?api_key=7Vx035OaZYE_EsqUhg&voice=Audrey&speed=6&text=" . urlencode($message)));
//        
//        $id = $response->book_id;
//        //echo $id;
//        
//        $status = "TTS RUNNING";
//        while($status == "TTS RUNNING")
//        {
//            $audio = json_decode(file_get_contents("https://www.yakitome.com/api/rest/audio?api_key=7Vx035OaZYE_EsqUhg&book_id=$id&format=mp3"));
//            //var_dump($audio);
//            
//            $status = $audio->status;
//
//        }
//        
//        $audio = json_decode(file_get_contents("https://www.yakitome.com/api/rest/audio?api_key=7Vx035OaZYE_EsqUhg&book_id=$id&format=mp3"));
//        $mp3 = $audio->audios[0];
//        echo $mp3;
//        exec("wget -O /tmp/tts.mp3 " . $audio->audios[0]);
//        exec("mplayer /tmp/tts.mp3");
//        exec("rm /tmp/tts.mp3");
        
//        exec("wget -O /tmp/tts.wav http://api.voicerss.org/?key=ff10f6799a484f0fa1b3744032ca43c3&c=wav&f=16khz_16bit_mono&hl=en-us&r=0&src=" . urlencode($message));
//        exec("aplay /tmp/tts.wav");
//        exec("rm /tmp/tts.wav");
        $chunk = [];
        
        $random = rand(1, 2);
        
        if ($random == 1)
        {
            $url[0] = "https://tts.neospeech.com/rest_1_1.php?method=ConvertSimple&email=me@joshwalls.co.uk&accountId=0e2a67153a&loginKey=LoginKey&loginPassword=96084cbdff0489466994&voice=TTS_NEOBRIDGET_DB&outputFormat=FORMAT_WAV&sampleRate=16&text=";
            $url[1] = "https://tts.neospeech.com/rest_1_1.php?method=GetConversionStatus&email=me@joshwalls.co.uk&accountId=0e2a67153a&conversionNumber=";
        }
        else
        {
            $url[0] = "https://tts.neospeech.com/rest_1_1.php?method=ConvertSimple&email=josh@wallsfamily.co.uk&accountId=004a11dcb6&loginKey=LoginKey&loginPassword=a624702570f18209e0d7&voice=TTS_NEOBRIDGET_DB&outputFormat=FORMAT_WAV&sampleRate=16&text=";
            $url[1] = "https://tts.neospeech.com/rest_1_1.php?method=GetConversionStatus&email=josh@wallsfamily.co.uk&accountId=004a11dcb6&conversionNumber=";
        }
        

        if (str_word_count($message) > 100)
        {
            $lineSplit = preg_replace( '~((?:\S*?\s){100})~', "$1\n", $message );

            $chunk = explode("\n", $lineSplit);
        }
        else
        {
            $chunk[0] = $message;
        }
        
       
        foreach($chunk as $sentance)
        {
            $response = simplexml_load_string(file_get_contents($url[0] . urlencode($sentance)));
      
            if ($response['resultCode'] != "0")
            {
                //exec("aplay /home/joshwalls/JarvisRT/includes/error.wav");
                exec("espeak " . escapeshellarg($sentance));
                return;
            }

            $audio = simplexml_load_string(file_get_contents($url[1] . urlencode($response['conversionNumber'])));
            $start_time = time();
            while($audio['downloadUrl'] == "")
            {
                if ((time() - $start_time) > 30)
                {
                    exec("aplay /home/joshwalls/JarvisRT/includes/error.wav");
                    return; // timeout, function took longer than 300 seconds
                    break;
                }
                $audio = simplexml_load_string(file_get_contents($url[1] . urlencode($response['conversionNumber'])));
            }
            exec("wget -O /tmp/tts.wav " . $audio['downloadUrl']);
            exec("aplay /tmp/tts.wav");
            exec("rm /tmp/tts.wav");
        }
       
        
        
    }
}

//say("You have a new notification:");
