<?php
require_once("includes/say.php");
say("Initialising.");
require_once("includes/const.php");
require_once("includes/checkNight.php");
require_once("includes/ordinal.php");
require_once("includes/getNotifications.php");
require_once("includes/switching.php");
require_once("includes/pushover.php");

//require('vendor/autoload.php');

//use WebSocket\Client;

//$client = new Client("wss://client.pushover.net/push");
//$client->send("login:". ID . ':' . SECRET . "\n");


say("Functions loaded.");

exec ("stty -F /dev/ttyACM0 cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts");

sleep(5);

say("Initialised serial port.");

$recieve = fopen("/dev/ttyACM0", "w+");
say("Made contact with serial port.");
sleep(5);
$line = fgets($recieve);
say("Successfully received string.");
echo $line;

mysql_connect("localhost", "root", "root") or die("Could not connect to database.");
mysql_select_db("auto") or die ("Could not find database.");
say("Connected to database.");

$userName = "Josh";
$computerName = "Jarvis";

$one = FALSE;
$two = FALSE;
$three = FALSE;
$four = FALSE;

$night = FALSE;

$unixTime = time();
$tempBool = FALSE;
$lightBool = FALSE;

$present = TRUE;


say("Done.");
sleep(1);
say("Welcome " . $userName . ". I am " . $computerName . ", your computer.");
$startTime = time();
$startTime1 = time();
$motionTime = time();
$noMotionTime = time();


define('QUEUE', 2865);
$queue = msg_get_queue(QUEUE);

$msg_type = NULL;
$msg = NULL;
$max_msg_size = 100;





if(!getNotifications())
    say ("You have no new notifications.");


$startTime = time();

while(true)
{
    $time = time(); 
        
    $c_time = time();
    $open = strtotime('Today 7:25am');
    $close = strtotime('Today 10pm');

    if ($c_time > $open && $c_time < $close)
    {
        if (($time - $startTime) >= 5)
        {

            getNotifications();

			$startTime = time();
        }
        
//        if(!empty($client->receive()))
//        {
//            if ($client->receive() == '!')
//            {
//                    getNotifications();
//            }
//            elseif($client->receive() == '#')
//            {
//                    echo (date("H:i:s", time()) . ": Pushover working.\n");
//            }
//            elseif($client->receive() == 'R')
//            {
//                    say("Reload request received");
//
//                    curl_setopt_array($ch = curl_init(), array(
//                      CURLOPT_URL => "https://api.pushover.net/1/messages.json",
//                      CURLOPT_POSTFIELDS => array(
//                            "token" => "aDJpXkt11SxNZ5aSq1b8QNxsXFo8ru",
//                            "user" => "fGKCKasiY1GkBPxLH3dijCyvdBsS42",
//                            "message" => "Reload request received.",
//                      ),
//                      CURLOPT_SAFE_UPLOAD => true,
//                    ));
//                    curl_exec($ch);
//                    curl_close($ch);
//
//                    die;
//            }
//        }
        
        

    }
    

    // Read the PHP queue
    $queue_status=msg_stat_queue($queue);
    while ($queue_status['msg_qnum']>0)
    {
        msg_receive($queue, 1, $msg_type, $max_msg_size, $msg);
        

        fwrite($recieve, $msg);
        if ($msg == '1')
        {
        	$query = ("UPDATE socket SET status = 1 WHERE CODE = '1';");
            mysql_query($query);
            switching("red lights", "on");
            $one = true;
        }
        elseif ($msg == '2')
        {
        	
            $query = ("UPDATE socket SET status = 1 WHERE CODE = '2';");
            mysql_query($query);
            switching("displays", "on");
            $two = true;
        }
        elseif ($msg == '3')
        {
        
            $query = ("UPDATE socket SET status = 1 WHERE CODE = '3';");
            mysql_query($query);
            switching("big light", "on");
            $three = true;
        }
        elseif ($msg == '4')
        {
        
            $query = ("UPDATE socket SET status = 1 WHERE CODE = '4';");
            mysql_query($query);
            switching("printer", "on");
            $four = true;
        }
        elseif ($msg == '5')
        {
        
            $query = ("UPDATE socket SET status = 0 WHERE CODE = '1';");
            mysql_query($query);
            switching("red light", "off");
            $one = false;
        }
        elseif ($msg == '6')
        {
        
            $query = ("UPDATE socket SET status = 0 WHERE CODE = '2';");
            mysql_query($query);
            switching("displays", "off");
            $two = false;
        }
        elseif ($msg == '7')
        {
        
            $query = ("UPDATE socket SET status = 0 WHERE CODE = '3';");
            mysql_query($query);
            switching("big light", "off");
            $three = false;
        }
        elseif ($msg == '8')
        {
            $query = ("UPDATE socket SET status = 0 WHERE CODE = '4';");
            mysql_query($query);
            switching("printer", "off");
            $four = false;
        }
        elseif ($msg == '?')
        {
            switching("LCD backlight", "on");
        }
        elseif ($msg == '!')
        {
            switching("LCD backlight", "off");
        }
        elseif ($msg == 'a')
        {
            say("Goodnight, " . $userName . ".");
        }
        elseif($msg == 'b')
        {
            switching("red lights and the displays", "on");
        }
        elseif ($msg == 'c')
        {
            switching("everything except the red lights", "off");
        }
        elseif ($msg == 'd')
        {
            say("I'll remove the distractions.");
        }
        elseif ($msg == 'g')
        {
            shell_exec("amixer set Master unmute");
            shell_exec("wget http://localhost/auto/power.php?e=n");
        }
        elseif ($msg == '[') 
        {
            shell_exec("amixer set Master mute");
        }
        elseif ($msg == ']')
        {
            shell_exec("amixer set Master unmute");
        }
        elseif ($msg == 'q')
        {
        	shell_exec("php /home/joshwalls/JarvisRT/morning/morning.php");
        }
        elseif ($msg == 'w')
        {
        	shell_exec("php /home/joshwalls/JarvisRT/tasks/check.php");
        }
        elseif ($msg == 'r')
        {
        	if(!getNotifications())
    			say ("You have no new notifications.");
        }
        elseif ($msg == 't')
        {
        	shell_exec("php /home/joshwalls/JarvisRT/tasks/inside.php");
        }
        elseif ($msg == 'y')
        {
        	shell_exec("php /home/joshwalls/JarvisRT/tasks/outside.php");
        }
        elseif ($msg == 'u')
        {
        	shell_exec("php /home/joshwalls/JarvisRT/tasks/speedtest.php");
        }
        

        $msg_type = NULL;
        $msg = NULL;
        $queue_status=msg_stat_queue($queue);
        sleep(1);
    }

    if(date("His", time()) == "215950")
    {
        say("Good night " . $userName . ". I\'ll turn everything off and stop talking.");
        shell_exec("curl http://server.local/auto/power.php?e=a");
        shell_exec("amixer set Master mute");
    }
    
    if(date("His", time()) == "060000")
    {
        //shell_exec("amixer set Master unmute");
    }
    
    
    $stream = fgets($recieve);
    //echo($stream);
    if ($stream != "")
    {
        
        $split = split(',', $stream);

        $temp = floatval($split[0]);
        $humidity = floatval($split[1]);
        $motion= intval($split[2]);
        $smoke = intval($split[3]);
        $code = intval($split[4]);
        $light = intval($split[5]);

        $time = time(); 
        
        if (($time - $startTime1) >= 30 && !is_nan($temp))
        {
            $query = "INSERT INTO data (time, temp, humidity, light) VALUES (
                '" . $time . "'," .
                "'" . $temp . "'," .
                "'" . $humidity . "'," .
                "'" . $light . "')"; 

            mysql_query($query);
            
            $ch2 = curl_init();
            curl_setopt($ch2, CURLOPT_URL, "https://api.thingspeak.com/update?key=BTQIXMIMJKW5ABYX&field1=" . strval($temp));
            curl_setopt($ch2, CURLOPT_VERBOSE, 0);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch2);
            curl_close($ch2);
            
            $ch3 = curl_init();
            curl_setopt($ch3, CURLOPT_URL, "https://api.thingspeak.com/update?key=BG3BLC6N04S2APLZ&field1=" . strval($humidity));
            curl_setopt($ch3, CURLOPT_VERBOSE, 0);
            curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch3);
            curl_close($ch3);
            
            $ch4 = curl_init();
            curl_setopt($ch4, CURLOPT_URL, "https://api.thingspeak.com/update?key=ZZSL3QUP9CNRZZN5&field1=" . strval(($light/1024)*100));
            curl_setopt($ch4, CURLOPT_VERBOSE, 0);
            curl_setopt($ch4, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch4);
            curl_close($ch4);

            
            $startTime1 = time();
            echo (date("H:i:s", time()) . ": " . "All systems working\n");
        }
        
//        if ($smoke == 1)
//        {
//            pushover("Smoke detected at " . date("D d M G:i:s") . ".\nEmergency electrical cutoff in 1 minute", "FIRE, FIRE!", '1');
//            say("Fire, Fire!");
//        }
        
//        if ($temp <= 20 && !is_nan($temp))
//        {
//            if ($tempBool == FALSE)
//            {
//                $random = rand(1, 5);
//                if ($random == 1)
//                {
//                    say("It's getting a bit chilly.");
//                }
//                elseif ($random == 2)
//                {
//                    say("The temperature has dropped.");
//                }
//                elseif ($random == 3)
//                {
//                    say("It's started to get a bit cold in here.");
//                }
//                elseif ($random == 4)
//                {
//                    say("The temperature is below 20 degrees celsius.");
//                }
//                elseif ($random == 5)
//                {
//                    say("It's so cold.");
//                }
//
//                $tempBool = TRUE;
//            }
//        }
//        else
//        {
//            $tempBool = FALSE;
//        }
        
        if ($code == '1')
        {
            $query = ("UPDATE socket SET status = 1 WHERE CODE = '1';");
            mysql_query($query);
        }
        elseif ($code == '2')
        {
            $query = ("UPDATE socket SET status = 1 WHERE CODE = '2';");
            mysql_query($query);
        }
        elseif ($code == '3')
        {
            $query = ("UPDATE socket SET status = 1 WHERE CODE = '3';");
            mysql_query($query);
        }
        elseif ($code == '4')
        {
            $query = ("UPDATE socket SET status = 1 WHERE CODE = '4';");
            mysql_query($query);
        }
        elseif ($code == '5')
        {
            $query = ("UPDATE socket SET status = 0 WHERE CODE = '1';");
            mysql_query($query);
        }
        elseif ($code == '6')
        {
            $query = ("UPDATE socket SET status = 0 WHERE CODE = '2';");
            mysql_query($query);
        }
        elseif ($code == '7')
        {
            $query = ("UPDATE socket SET status = 0 WHERE CODE = '3';");
            mysql_query($query);
        }
        elseif ($code == '8')
        {
            $query = ("UPDATE socket SET status = 0 WHERE CODE = '4';");
            mysql_query($query);
        }
        
        
//        if ($light <= 200)
//        {
//            if (!checkNight())
//            {
//                if ($lightBool == FALSE)
//                {
//                    
//                    $random = rand(1, 5);
//                    if ($random == 1)
//                    {
//                        say("It's getting a bit dark.");
//                    }
//                    elseif ($random == 2)
//                    {
//                        say("The light level has dropped.");
//                    }
//                    elseif ($random == 3)
//                    {
//                        say("It's started to get a bit dark in here.");
//                    }
//                    elseif ($random == 4)
//                    {
//                        say("The light level is below 30 percent.");
//                    }
//                    elseif ($random == 5)
//                    {
//                        say("It's so dark.");
//                    }
//                    $lightBool = TRUE;
//                }
//            }
//        }
//        else
//        {
//            $lightBool = FALSE;
//        }
        
//         if ($motion == 0)
//         {
//             if (checkNight())
//             {
//                 if ($present)    
//                 {
//                 	if ($noMotionTime <= time() - 20)
//                     {
//                         shell_exec("curl http://localhost/auto/power.php?e=5");
//                         $noMotionTime = time();
//                         $present = FALSE;
//                     }
//                 }
//             }
//             else
//             {
//                 if ($present)
//                 {
//                     if ($noMotionTime <= time() - 600)
//                     {
//                         shell_exec("curl http://localhost/auto/power.php?e=f");
//                         say("Goodbye, " . $userName . ".");
//                         pushover("Goodbye, " . $userName . ".", "Goodbye", "0");
//                         $noMotionTime = time();
//                         $present = FALSE;
//                     }
//                 }
//             }
//         }
        

        
        // if ($motion == 1)
//         {
//             
//             if (checkNight())
//             {
//                 if ($motionTime <= time() - 20)
//                 {
//                     shell_exec("curl http://localhost/auto/power.php?e=1");
//                     $motionTime = time();
//                     $noMotionTime = time();
//                     $present = TRUE;
//                 }
//             }
//             else
//             {
//                 if ($motionTime <= time() - 600)
//                 {
//                 	shell_exec("curl http://localhost/auto/power.php?e=g");
//                     say("Welcome back, " . $userName . ".");
//                     pushover("Welcome back, " . $userName . ".", "Hello", "0");
//                     $motionTime = time();
//                     $noMotionTime = time();
//                     $present = TRUE;
//                 }
//             }
//         }
    }
    else
    {
        echo ("Lost contact with serial port. Dying.");
        say("Lost contatct with serial port. Dying");
        pushover("Lost contatct with serial port. Dying", "Error", "1");
        die;
    }
   
}
