<?php



require "vendor/autoload.php";

$access_token = 'EIgKy1pMFSuDKDxGcYXYn5F+H/XKu79X3DUVisrJuYVbmCxwVGqvd/aoCP+DVJ42VYLCtmo+ITVQ7cjvFczdjMhozScFsF9SyIUdexb95Thze0hzUOu2AbpnehmF9YsGpXKlWwi6gq8QC9BHJU2wAQdB04t89/1O/w1cDnyilFU=';

$channelSecret = '6662cdbecd72dbe05dede37dfbd998de';

$pushID = 'U3c59d29559ead1555db3725a75222fd2';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('hello world');
$response = $bot->pushMessage($pushID, $textMessageBuilder);

echo $response->getHTTPStatus() . ' ' . $response->getRawBody();







