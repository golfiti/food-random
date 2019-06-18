<?php
    $accessToken = "EIgKy1pMFSuDKDxGcYXYn5F+H/XKu79X3DUVisrJuYVbmCxwVGqvd/aoCP+DVJ42VYLCtmo+ITVQ7cjvFczdjMhozScFsF9SyIUdexb95Thze0hzUOu2AbpnehmF9YsGpXKlWwi6gq8QC9BHJU2wAQdB04t89/1O/w1cDnyilFU=";//copy Channel access token ตอนที่ตั้งค่ามาใส่
    
    $content = file_get_contents('php://input');
    $arrayJson = json_decode($content, true);
    
    $arrayHeader = array();
    $arrayHeader[] = "Content-Type: application/json";
    $arrayHeader[] = "Authorization: Bearer {$accessToken}";
    
    //รับข้อความจากผู้ใช้
    // $message = $arrayJson['events'][0]['message']['text'];
    $messageType = $arrayJson['events'][0]['message']['type'];    

    if ($messageType == "location"){
        $myLat = $arrayJson['events'][0]['message']['latitude'];    
        $myLon = $arrayJson['events'][0]['message']['longitude'];    

        $jsonAQI = json_decode(getAQI($myLat,$myLon), true);
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = 'Nearest AQI : ' . $jsonAQI['data']['state'] . ' is ' . '[' . $jsonAQI['data']['current']['pollution']['aqius'] . ']';
        // $arrayPostData['messages'][0]['text'] = $myLat . $myLon;

        replyMsg($arrayHeader,$arrayPostData);
    }
    

    function getAQI($currentLat,$currentLon){
        echo $currentLat;
        echo $currentLon;
        // api.airvisual.com/v2/nearest_city?lat={{LATITUDE}}&lon={{LONGITUDE}}&key={{YOUR_API_KEY}}
        $strUrl = "http://api.airvisual.com/v2/nearest_city?lat=48.02&lon=-50.20&key=5uE3y4hLFGFbDmfto";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_GET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
        echo $strUrl;
        echo $result;
        return $result;
    }




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
   exit;
?>