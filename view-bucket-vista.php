<?php
$filesInDirectory = array();
$dir = './originales';
$cdir = scandir($dir);
foreach ($cdir as $key => $value) {
    if ($value != '..' and $value != '.') {
        if (! is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
            array_push($filesInDirectory, $value);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Uploaded</title>
<link rel="stylesheet" href="./statics/style.css">
</head>
<body>

<?php
require './nav.php';
?>

	<section>
		<div class="vertical">
			<h3>Choose an image to upload to your Bucket</h3>
			<div class="three-positions">
    			<?php

foreach ($filesInDirectory as $value) {
        ?>
    				<a href="./logic-bucket-subir.php?toupload=<?=$value?>">
    					<img class="img-max-size" src="./originales/<?=$value?>" />
    				</a>
    			<?php

}
    ?>
			</div>
		</div>
	</section>

</body>
</html>