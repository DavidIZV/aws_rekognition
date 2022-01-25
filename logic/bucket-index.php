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

$analysis = BucketClient::indexFaces('us-east-1', $bucket, $imageName, $collectionName, $key, $secret, $token);

echo json_encode([
        "ok" => true,
        "faces" => $analysis["FaceRecords"]
]);