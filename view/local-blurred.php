<?php
require './../autoload.php';

$imageName = Util::getParam("toanalyze");
$filesInDirectory = array();
$dir = './../originales/blur/' . substr($imageName, 0, strlen($imageName) - 4);
$cdir = scandir($dir);
foreach ($cdir as $key => $value) {
    if ($value != '..' and $value != '.') {
        if (! is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
            array_push($filesInDirectory, $dir . DIRECTORY_SEPARATOR . $value);
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

	<section class="no-wrap">
		<div class="w-100 gallery">
			<h3 class="w-100">Blurred images</h3>
			<?php if(count($filesInDirectory) > 0){ ?>
        	<?php foreach ($filesInDirectory as $value) { ?>
			<div class="w-30">
				<img id="myimage" class="<?=$imageName?> img-700-max" src="<?=$value?>" />
			</div>
        	<?php } ?>
			<?php } else { ?>
			<div class="w-30">
				<p>This image wasn't blurred before.</p>
			</div>
			<?php } ?>
		</div>
	</section>

</body>
</html>