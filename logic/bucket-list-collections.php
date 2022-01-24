<?php
require './../autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$key = $_ENV['aws_access_key_id'];
$secret = $_ENV['aws_secret_access_key'];
$token = $_ENV['aws_session_token'];

$collections = BucketClient::listCollections('us-east-1', $key, $secret, $token);

echo json_encode([
        "ok" => true,
        "collections" => $collections["CollectionIds"]
]);