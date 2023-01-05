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
    global $fileCsv;
    global $client;
    $row = 0;
    $rawData = array();
    $date = date_create($dateNow);
    date_add($date, date_interval_create_from_date_string('-1 hours'));
    $dateOld = date_format($date, 'Y-m-d H:i:s');
    echo "Datetime Start => " . $dateOld . "\n";
    echo "Datetime End   => " . $dateNow . "\n";
    if (($handle = fopen($fileCsv, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
            if (strtotime($data[9]) >= strtotime($dateOld) && strtotime($data[9]) <= strtotime($dateNow)) {
                $row++;
                $rawData["data"][] = [
                    "datetime" => $data[9],
                    "server" => $client,
                    "uniqueid" => $data[16],
                    "total_data" => count($data)
                ];
            }
        }
        echo "Total Data => " . $row . "\n";
    }
}


function senTele($BodyMessage)
{
    $botToken = "5763080979:AAGpE6w9tN1mt4Mji2d0CPArCUub3HBDK8M";
    $chatID = -623402830;
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
