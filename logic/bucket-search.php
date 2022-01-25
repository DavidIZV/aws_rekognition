<?php
require './../autoload.php';

$imageName = Util::getParam("toanalyze");
$filename = __DIR__ . '/../originales/' . $imageName;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$key = $_ENV['aws_access_key_id'];
$secret = $_ENV['aws_secret_access_key'];
$token = $_ENV['aws_session_token'];

$collectionName = Util::getParam("collection-to");
$bucket = 'aws-bucket-php-dawid';
$accurancity = Util::getParam("accurancity");

$analysis = BucketClient::searchFacesByImage('us-east-1', $bucket, $imageName, $collectionName, $key, $secret, $token,
        $accurancity);

$urls = array();

foreach ($analysis["FaceMatches"] as $face) {
    $newUrl = $face["Face"]["ExternalImageId"];
    if (! in_array($newUrl, $urls)) {
        array_push($urls, $newUrl);
    }
}

echo json_encode([
        "ok" => true,
        "matches" => $analysis["FaceMatches"],
        "urls" => $urls
]);