<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Uploaded</title>
<link rel="stylesheet" href="./style.css">
</head>
<body>

	<section>

		<div>
			<form enctype="multipart/form-data" method="POST"
				action="./subir.php">

				<h1>Upload image</h1>

				<input type="file" placeholder="Choose image" name="image" required>

				<button type="submit" class="btn">Upload</button>
			</form>
		</div>

	</section>

</body>
</html>