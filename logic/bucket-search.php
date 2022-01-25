<?php
require './../autoload.php';

$imageName = Util::getParam("toanalyze");
$filename = __DIR__ . '/../originales/' . $imageName;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$key = $_ENV['aws_access_key_id'];
$secret = $_ENV['aws_secret_access_key'];
$token = $_ENV['aws_session_token'];

$collectionName = $_GET["collection-to"];

$bucket = 'aws-bucket-php-dawid';

$analysis = BucketClient::searchFacesByImage('us-east-1', $bucket, $imageName, $collectionName, $key, $secret, $token);

$urls = array();

foreach ($analysis["FaceMatches"] as $face) {
    array_push($urls, $face["Face"]["ExternalImageId"]);
}

echo json_encode([
        "ok" => true,
        "matches" => $analysis["FaceMatches"],
        "urls" => $urls
]);