<?php
$filesInDirectory = array();
$dir = './../originales/blur';
if (is_dir($dir)) {
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if ($value != '..' and $value != '.') {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $dirSecond = scandir($dir . DIRECTORY_SEPARATOR . $value);
                foreach ($dirSecond as $keyDeeper => $valueDeeper) {
                    if ($valueDeeper != '..' and $valueDeeper != '.') {
                        if (! is_dir($dir . DIRECTORY_SEPARATOR . $value . DIRECTORY_SEPARATOR . $valueDeeper)) {
                            array_push($filesInDirectory, $value . DIRECTORY_SEPARATOR . $valueDeeper);
                        }
                    }
                }
            }
        }
    }
}
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
		<div class="vertical w-100">
			<h3 class="w-100">All blurred images saved</h3>
			<div class="w-100 gallery">
        		<?php foreach ($filesInDirectory as $value) { ?>
        		<a href="./../logic/bucket-subir.php?toupload=<?=$value?>">
					<img src="./../originales/blur/<?=$value?>" />
				</a>
        		<?php } ?>
    		</div>
		</div>
	</section>

</body>
</html>