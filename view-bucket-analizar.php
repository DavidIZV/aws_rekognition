<?php
$filesInDirectory = array ();
$dir = './originales';
$cdir = scandir ( $dir );
foreach ( $cdir as $key => $value ) {
	if ($value != '..' and $value != '.') {
		if (! is_dir ( $dir . DIRECTORY_SEPARATOR . $value )) {
			array_push ( $filesInDirectory, $value );
		}
	}
}
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
			<h3>Choose an image to analyze</h3>
			<p>This part is still in construction</p>
		</div>
	</section>

</body>
</html>