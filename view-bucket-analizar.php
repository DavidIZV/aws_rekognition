<?php
require './autoload.php';

$filesInDirectory = array();
$imageName = Util::getParam("toanalyze");
$filename = __DIR__ . '\\originales\\' . $imageName;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$key = $_ENV['aws_access_key_id'];
$secret = $_ENV['aws_secret_access_key'];
$token = $_ENV['aws_session_token'];

$bucket = 'aws-bucket-php-dawid';

$analysis = BucketClient::analyzeImage('us-east-1', $filename, $key, $secret, $token, $bucket, $imageName);

$faces = Mapper::translateDataAws($analysis);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Uploaded</title>
<link rel="stylesheet" href="./style.css">
</head>
<body>

	<section>
		<div class="vertical">
			<h3>Image to analyze</h3>
			<div class="all-screen">
				<img class="img-max-size" src="./originales/<?=$imageName?>" />
			</div>
		</div>
		<div class="vertical right-side">
			<p><?=var_dump($faces[0]->array())?></p>
		</div>
	</section>

</body>
</html>