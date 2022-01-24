<?php
require './../autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

$key = $_ENV['aws_access_key_id'];
$secret = $_ENV['aws_secret_access_key'];
$token = $_ENV['aws_session_token'];

$collectionName = $_GET["collection-to"];

$analysis = BucketClient::deleteCollection('us-east-1', $collectionName, $key, $secret, $token);

if ($analysis["StatusCode"] != 200) {
    echo json_encode([
            "ok" => false,
            "creation" => $analysis,
            "collections" => $collections
    ]);
    die();
}

$collections = BucketClient::listCollections('us-east-1', $key, $secret, $token);

echo json_encode([
        "ok" => true,
        "creation" => json_encode($analysis),
        "collections" => $collections["CollectionIds"]
]);