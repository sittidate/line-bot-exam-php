<?php
$access_token = 'IoXEpi6tL2jr5m7sipCpE3MUGDn5jxxQDWxo2lRrYx5bFyBbPJo6IQHEiVwArKVk7aFKHbwBvAnNAC72jy608KpCHzwucSOb7dC1GO7UtvAoQKb19wZPdEclNE8/C1OxqbAJQ6h+1Rs5kU7TUP4MGQdB04t89/1O/w1cDnyilFU=';


$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;