<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Uploaded</title>
<link rel="stylesheet" href="./statics/style.css">
</head>
<body>

<?php require './nav.php'; ?>

	<section>

		<div class="w-100">
			<form enctype="multipart/form-data" method="POST" action="./logic/local-subir.php" >

				<h1>Upload image</h1>

				<input type="file" placeholder="Choose image" name="image" required>

				<button type="submit" class="btn">Upload</button>
			</form>
		</div>

	</section>

	<section>

		<div class="w-100 vertical ">
			<h1 class="w-60">Implemented functionalities</h1>
			<ol class="w-60 vertical list">
				<li><p>Facial recognition</p></li>
				<li><p>Auto blur for faces of minors</p></li>
				<li><p>Personalized blur</p></li>
				<li><p>Celebrities recognition and info links</p></li>
				<li><p>Create, remove and list collections</p></li>
				<li><p>Indexation of faces in collections</p></li>
				<li><p>Search of familiar faces in collections</p></li>
			</ol>
		</div>

	</section>

</body>
</html>