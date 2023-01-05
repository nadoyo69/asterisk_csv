#!/usr/bin/php -q
<?php
echo "-------------------------- NADOYO --------------------------\n\n";
date_default_timezone_set("Asia/Jakarta");
$sDate = date("Y-m-d 06:00:00");
$eDate = date("Y-m-d 21:59:59");
$dateNow = date("Y-m-d H:i:s");
$fileCsv = "/var/log/asterisk/cdr-csv/Master.csv";
$client = exec("echo $(hostname -s |  cut -d'-' -f 2-4)");

if (count($argv) != 1) {
    if ($argv[1] == "--PUSH-TOTAL-CALL") {
        pushTotalCall();
    } else {
        showHelp();
    }
} else {
    showHelp();
}

function showHelp()
{
    echo ("    --help               Example => alert_call_center --help\n");
    echo ("    --PUSH-TOTAL-CALL    Send Data To Dashboard Trafik Call\n");
}


function pushTotalCall()
{
    global $dateNow;
    global $eDate;
    global $sDate;
    global $fileCsv;
    $row = 0;
    $totalDataCall = 0;
        if (($handle = fopen($fileCsv, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
                $num = count($data);
                $row++;
                if (strtotime($data[9]) <= strtotime($sDate)  &&  strtotime($eDate) >= strtotime($data[9])) {
                    $totalDataCall++;
                }
            }
            echo "Total All Data Call => " . $row . "\n";
            if ($totalDataCall > 0) {
                fclose($handle);
                $BodyMessage = "#ALERT ⚠️
[!] Call Prosess 
[!] Ada $totalDataCall Call
        
#SERVER_" . exec("hostname | sed -E 's/-|\./_/g'") . "";
                senTele($BodyMessage);
            }
        }
    
}


function senTele($BodyMessage)
{
    $botToken = "<TOKEN BOT>";
    $chatID = <CHATID>;
    $website = "https://api.telegram.org/bot" . $botToken;

    $params = [
        'chat_id' => $chatID,
        'text' => $BodyMessage
    ];
    $ch = curl_init($website . '/sendMessage');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    curl_close($ch);
}



echo "\n-------------------------- NADOYO --------------------------\n";
