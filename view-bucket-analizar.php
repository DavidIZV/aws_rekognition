<?php
require './autoload.php';

$filesInDirectory = array();
$imageName = Util::getParam("toanalyze");
$filename = __DIR__ . '/originales/' . $imageName;

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
<link rel="stylesheet" href="./statics/style.css">
<link rel="stylesheet" href="https://unpkg.com/jcrop/dist/jcrop.css">
<script src="https://unpkg.com/jcrop" type="text/javascript"></script>
</head>
<body>

<?php require './nav.php'; ?>

	<section>
		<div class="w-80 gallery">
			<h3 class="w-100">Image to analyze</h3>
			<div class="w-100">
				<img id="myimage" src="./originales/<?=$imageName?>" />
			</div>
		</div>
		<div class="w-20">
			<p><?=var_dump($faces[0]->array())?></p>
		</div>
	</section>

	<div class="none">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
		<script src="./statics/aws-jcrop.js" type="text/javascript"></script>
	</div>

</body>
</html>