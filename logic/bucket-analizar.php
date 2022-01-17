<?php
require './../autoload.php';

$imageName = Util::getParam("toanalyze");
$filename = __DIR__ . '/../originales/' . $imageName;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$key = $_ENV['aws_access_key_id'];
$secret = $_ENV['aws_secret_access_key'];
$token = $_ENV['aws_session_token'];

$bucket = 'aws-bucket-php-dawid';

$analysis = BucketClient::analyzeImage('us-east-1', $filename, $key, $secret, $token, $bucket, $imageName);

$faces = Mapper::translateDataAws($analysis);

echo json_encode([
        "faces" => $faces
]);