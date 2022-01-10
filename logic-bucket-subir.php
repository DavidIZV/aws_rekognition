<?php
require './autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$key = $_ENV['aws_access_key_id'];
$secret = $_ENV['aws_secret_access_key'];
$token = $_ENV['aws_session_token'];

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$s3 = BucketClient::createAwsClient('us-east-1', $key, $secret, $token);

$imageName = $_GET["toupload"];

$bucket = 'aws-bucket-php-dawid';
$keyname = $imageName;
$filename = __DIR__ . '/originales/' . $imageName;

$s3 = BucketClient::upload($s3, $bucket, $keyname, $filename);

Util::redirect("view-bucket-analizar.php?toanalyze=" . $imageName);