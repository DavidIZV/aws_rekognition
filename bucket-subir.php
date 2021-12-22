<?php
require './autoload.php';

$key = "ASIAR6YZL2NDF3ATO23E";
$secret = "RakJSADRQoFL9xDqaRVCNS02x1w5Rz9rssruMnba";
$token = "FwoGZXIvYXdzEEMaDAcbhQ/0ycrcgaCkvCLKAUOgOM4WFRxJlEN+srI0gzrparERMPZOBshyI8CltQs4Y8hk7i0U33P5T3MDKU/zHW74EMhfz5QPucu4wmzbeMFLyVIuD9eGC+X5/mfby+UgENop/Ve2Ty0TJ/EFQNNftVqz6G2LasFfen62vhDBxFS5Rk7jAwl2RGYrSZI3XLCatOVpHSZiGpqvs3ty/ujR3vedIHw5K28hgtUhWVj4ufM/j2b8e4SyqJvcsrDrcU6tFvykCM2wcNhe5gKkBYt5zRPAUFNTMu+srIko6+WLjgYyLeAN57g2+G8LFh0z2RyugWGSFgOuvweMyLScFh8mRLcnIHlUs/7NgHoRDhdo6Q==";

$s3 = BucketClient::createAwsClient ( 'us-east-1', $key, $secret, $token );

$imageName = $_GET ["toupload"];

$bucket = 'aws-bucket-php-dawid'; // '*** Your Bucket Name ***';
$keyname = $imageName; // '*** Your Object Key ***';
$filename = __DIR__ . '/originales/' . $imageName; // '*** Path to and Name of the File to Upload ***';

$s3 = BucketClient::upload ( $s3, $bucket, $keyname, $filename );

Util::redirect ( "bucket-analizar.php" );