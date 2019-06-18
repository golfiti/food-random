<?php

$accessToken = "8M8NhhzAGoZ7dUznlVQYqWvUADqpM5CcFXgGSBixWtxNAzeFOy1vuTyF8EpAKUfvVYLCtmo+ITVQ7cjvFczdjMhozScFsF9SyIUdexb95Th9MjjS5DjjjuiM3LlddDxIEFWLLs94B5cRlvk/q8AVLQdB04t89/1O/w1cDnyilFU=";
$arrayJson = json_decode($content, true);

$arrayHeader = array();
$arrayHeader[] = "Content-Type: application/json";
$arrayHeader[] = "Authorization: Bearer {$accessToken}";

replyMsg($arrayHeader,$arrayPostData);

function replyMsg($arrayHeader,$arrayPostData){
    $strUrl = "https://api.line.me/v2/bot/message/reply";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);    
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arrayPostData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close ($ch);
}

?>