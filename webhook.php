<?php
    $accessToken = "EIgKy1pMFSuDKDxGcYXYn5F+H/XKu79X3DUVisrJuYVbmCxwVGqvd/aoCP+DVJ42VYLCtmo+ITVQ7cjvFczdjMhozScFsF9SyIUdexb95Thze0hzUOu2AbpnehmF9YsGpXKlWwi6gq8QC9BHJU2wAQdB04t89/1O/w1cDnyilFU=";
    $arrayJson = json_decode($content, true);
        
    $arrayHeader = array();
    $arrayHeader[] = "Content-Type: application/json";
    $arrayHeader[] = "Authorization: Bearer {$accessToken}";

    $messageType = $arrayJson['events'][0]['message']['type'];    

    // echo getAQI();
    // echo 'Nearest AQI : ' . $jsonAQI['data']['state'] . ' is ' . '[' . $jsonAQI['data']['current']['pollution']['aqius'] . ']';

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
	
function getAQI($currentLat,$currentLon){
        echo $currentLat;
        echo $currentLon;
        // api.airvisual.com/v2/nearest_city?lat={{LATITUDE}}&lon={{LONGITUDE}}&key={{YOUR_API_KEY}}
        $strUrl = "https://api.airvisual.com/v2/nearest_city?lat=$currentLat&lon=$currentLon&key=5uE3y4hLFGFbDmfto";
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
    

   exit;
?>