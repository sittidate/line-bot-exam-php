<?php // callback.php
require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');
$access_token = 'IoXEpi6tL2jr5m7sipCpE3MUGDn5jxxQDWxo2lRrYx5bFyBbPJo6IQHEiVwArKVk7aFKHbwBvAnNAC72jy608KpCHzwucSOb7dC1GO7UtvAoQKb19wZPdEclNE8/C1OxqbAJQ6h+1Rs5kU7TUP4MGQdB04t89/1O/w1cDnyilFU=';
// Request Format
/*
{  "events": [
    {    
	  "replyToken": "nHuyWiB7yP5Zw52FIkcQobQuGDXCTA",        
	  "type": "message",        
	  "timestamp": 1462629479859,        
	  "source": 
		  {             
		  "type": "user",             
		  "userId": "U206d25c2ea6bd87c17655609a1c37cb8"         
		  },         
	  "message": 
		  {             
		  "id": "325708",             
		  "type": "text",             
		  "text": "Hello, world"          
		  }      
	}  
	]
}
*/
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$userid = $event['source']['userId'];
			$groupid = $event['source']['groupId'];
			
			$url = 'https://api.line.me/v2/bot/group/'.$groupid.'/member/'.$userid;
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
		
			$text = "<";
			$res = json_decode($result, true);
			$text = $text.$res['displayName']."\n";
			$text = $text." ".$event['message']['id'].'\n';
			
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			//$url = 'https://api.line.me/v2/bot/message/broadcast';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
		}
	}
}
echo "OK";
