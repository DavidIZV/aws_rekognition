<?php
require './../autoload.php';

$imageName = Util::getParam("toanalyze");
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Uploaded</title>
<link rel="stylesheet" href="./../statics/style.css">
<link rel="stylesheet" href="https://unpkg.com/jcrop/dist/jcrop.css">
<script src="https://unpkg.com/jcrop" type="text/javascript"></script>
</head>
<body>

<?php require './../nav.php'; ?>

	<section>
		<div class="w-80 gallery">
			<h3 class="w-100">Image to analyze</h3>
			<div class="w-100">
				<img id="myimage" class="img-no-max <?=$imageName?>" src="./../originales/<?=$imageName?>" />
			</div>
		</div>
		<div class="w-20 vertical">
			<h3 class="w-100">AWS data</h3>
			<p class="aws-data"></p>
		</div>
	</section>

	<div class="none">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
		<script src="./../statics/aws-jcrop.js" type="text/javascript"></script>
	</div>

</body>
</html>