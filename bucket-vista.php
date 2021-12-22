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
			<h3>Choose an image to upload to your Bucket</h3>
			<?php
			foreach ( $filesInDirectory as $value ) {
				?>
			<p>
				<a href="./bucket-subir.php?toupload=<?=$value?>"><?=$value?></a>
			</p>
			<?php
			}
			?>
		</div>
	</section>

</body>
</html>