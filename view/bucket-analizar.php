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

	<section class="no-wrap">
		<div class="w-100 gallery">
			<h3 class="w-100">Image to analyze</h3>
			<div class="w-100">
				<img id="myimage" class="<?=$imageName?> img-700-max" src="./../originales/<?=$imageName?>" />
			</div>
			<div class="w-100">
				<button type="button" class="btn btn-to-blurr">Blur this image</button>
				<button type="button" class="btn btn-to-blurr-saved">Blur saved</button>
				<button type="button" class="btn btn-to-delete">Delete active box</button>
				<button type="button" class="btn btn-to-hide-celebrities">Hide or show celebrity marks</button>
				<input type="text" class="btn btn-to-write" onclick="return false;" placeholder="Write collection name" />
				<button type="button" class="btn btn-to-create-collection">Create collection</button>
			</div>
		</div>
		<div class="w-20 gallery celebrities">
			<h3 class="w-100">Celebrities</h3>
			<div class="w-100">
				<ul>
					<li>No detectados</li>
				</ul>
			</div>
		</div>
		<div class="w-20 gallery collections">
			<h3 class="w-100">Collections</h3>
			<div class="w-100">
				<ul>
					<li>No detectados</li>
				</ul>
			</div>
		</div>
		<div class="w-80 gallery">
			<h3 class="w-100">Result</h3>
			<div class="w-100 newImage"></div>
		</div>
		<div class="w-100 vertical">
			<h3 class="w-100">AWS data</h3>
			<p class="aws-data"></p>
		</div>
	</section>

	<div class="none">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" type="text/javascript"></script>
		<script src="./../statics/util.js" type="text/javascript"></script>
		<script src="./../statics/aws-faces.js" type="text/javascript"></script>
		<script src="./../statics/aws-celebrities.js" type="text/javascript"></script>
		<script src="./../statics/aws-collections.js" type="text/javascript"></script>
	</div>

</body>
</html>