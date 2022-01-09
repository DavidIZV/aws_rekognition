<?php
require './autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$key = $_ENV['aws_access_key_id'];
$secret = $_ENV['aws_secret_access_key'];
$token = $_ENV['aws_session_token'];

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$s3 = BucketClient::createAwsClient ( 'us-east-1', $key, $secret, $token );

$imageName = $_GET ["toupload"];

$bucket = 'aws-bucket-php-dawid'; // '*** Your Bucket Name ***';
$keyname = $imageName; // '*** Your Object Key ***';
$filename = __DIR__ . '/originales/' . $imageName; // '*** Path to and Name of the File to Upload ***';

$s3 = BucketClient::upload ( $s3, $bucket, $keyname, $filename );

Util::redirect ( "view-bucket-analizar.php" );