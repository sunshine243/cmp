<?php

require "vendor/autoload.php";

use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

$data = json_decode(file_get_contents("php://input"));
$text = $data->text;

$client = new Client("AIzaSyBtZ89FsQJySV1p7Du_g3LdlNijSLaa8ss");

$response = $client->geminiPro()->generateContent(
    new TextPart($text)
);

echo $response->text();
