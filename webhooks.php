<?php
    $accessToken = "8M8NhhzAGoZ7dUznlVQYqWvUADqpM5CcFXgGSBixWtxNAzeFOy1vuTyF8EpAKUfvVYLCtmo+ITVQ7cjvFczdjMhozScFsF9SyIUdexb95Th9MjjS5DjjjuiM3LlddDxIEFWLLs94B5cRlvk/q8AVLQdB04t89/1O/w1cDnyilFU=";//copy Channel access token ตอนที่ตั้งค่ามาใส่
    
    $content = file_get_contents('php://input');
    $arrayJson = json_decode($content, true);
        
    $arrayHeader = array();
    $arrayHeader[] = "Content-Type: application/json";
    $arrayHeader[] = "Authorization: Bearer {$accessToken}";


    $messageType = $arrayJson['events'][0]['message']['type'];    

	if ($messageType == "location"){
        $myLat = $arrayJson['events'][0]['message']['latitude'];    
        $myLon = $arrayJson['events'][0]['message']['longitude'];    

        $aqiJSON = json_decode(getAQINearestStation($myLat,$myLon), true);
        
        $usAQI = $aqiJSON['data']['current_measurement']['aqius'];
        $nearestAQIStation = $aqiJSON['data']['name'];
        $temp = $aqiJSON['data']['current_weather']['tp'];
        $perception = $aqiJSON['data']['current_weather']['hu'];
        $note = noteMessageRandom($perception,$temp);

        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";

        if ($note != "") {
            $arrayPostData['messages'][0]['text'] = "📍$nearestAQIStation \n🌫 $usAQI us aqi  \n🌡 $temp °C  \n🌧 $perception%" . "\n $note";
        } else {
            $arrayPostData['messages'][0]['text'] = "📍$nearestAQIStation \n🌫 $usAQI us aqi  \n🌡 $temp °C  \n🌧 $perception%";
        }


        replyMsg($arrayHeader,$arrayPostData);
    }
    
function noteMessageRandom($perception,$temp) {

        if ($perception >= "85") {
            $arrayRainText = array();
            $arrayRainText[] = "ฝนน่าจะตกนะ...หาร่มด้วย ☂️";
            $arrayRainText[] = "เบื่อแล้วหน้าฝน ตอนนี้สนแค่หน้าเธอ...";
            $arrayRainText[] = "ฝนตกก็ชอบเหม่อ...หน้าเธอก็ชอบมอง";
            $arrayRainText[] = "โดนฝนอ่ะเป็นไข้...แต่ถ้าโดนใจอ่ะเป็นเธอ";
            $arrayRainText[] = "ฟ้าหลังฝน ถนนย่อมเปียกเสมอ 🛣";

            return $arrayRainText[array_rand($arrayRainText)];
        } 
        else {
            return "";
        }
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
    
function getAQINearestStation($currentLat,$currentLon) {

    $airVistualHeader = array();
    $airVistualHeader[] = "Content-Type: application/json";
    $airVistualHeader[] = "user-agent: AirVisual/5.0.1 (com.airvisual.airvisual; build:5.0.1.25; iOS 12.2.0) Alamofire/4.7.3";
    $airVistualHeader[] = "accept: */*";
    $airVistualHeader[] = "x-aqi-index: us";
    $airVistualHeader[] = "x-user-timezone: Asia/Bangkok";
    $airVistualHeader[] = "x-user-lang: th-TH";
    $airVistualHeader[] = "x-api-token: Hu/FAo/FejEFaG2utqqqWfjY/tt2gjWVOssRcFVy+k1clucty5eSfgrGJUelmClqPNR+kLjq3qit/FcW/c4KQg==";


    $currentLocation->lat = $currentLat;
    $currentLocation->lon = $currentLon;
    $currentLocation = json_encode($currentLocation);
    
    $strUrl = "https://api3.openairdata.org/api/v4/nearest";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $airVistualHeader);    
    curl_setopt($ch, CURLOPT_POSTFIELDS,$currentLocation);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close ($ch);
    $arrayJson = json_decode($result, true);
    $nearStationId = $arrayJson['data']['id'];    
    $aqiResult = getAQIFrom($nearStationId);
    return $aqiResult;
}

function getAQIFrom($cityId){

        $airVistualHeader = array();
        $airVistualHeader[] = "Content-Type: application/json";
        $airVistualHeader[] = "user-agent: AirVisual/5.0.1 (com.airvisual.airvisual; build:5.0.1.25; iOS 12.2.0) Alamofire/4.7.3";
        $airVistualHeader[] = "accept: */*";
        $airVistualHeader[] = "x-aqi-index: us";
        $airVistualHeader[] = "x-user-timezone: Asia/Bangkok";
        $airVistualHeader[] = "x-user-lang: th-TH";
        $airVistualHeader[] = "x-api-token: Hu/FAo/FejEFaG2utqqqWfjY/tt2gjWVOssRcFVy+k1clucty5eSfgrGJUelmClqPNR+kLjq3qit/FcW/c4KQg==";
    
        $strUrl = "https://api3.openairdata.org/api/v3/station/id?id=$cityId";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $airVistualHeader);
        curl_setopt($ch, CURLOPT_GET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
        return $result;
}
   exit;
?>
