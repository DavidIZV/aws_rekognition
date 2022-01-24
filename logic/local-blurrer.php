<?php
require './../autoload.php';

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$coords = $input["coords"];

$imageName = $input["imageName"];
$imageNameFilename = __DIR__ . '/../originales/' . $imageName;
$ext = pathinfo($imageNameFilename, PATHINFO_EXTENSION);

$imageBlurredName = substr($imageName, 0, strlen($imageName) - 4);
$directory = './../originales/blur/' . $imageBlurredName . "/";
Util::createDirIfNotExists($directory);
$newName = uniqid('image_');
$imageBlurredNameFilename = __DIR__ . $directory . $newName . "." . $ext;
$imageBlurredNameHref = $directory . $newName . "." . $ext;

if (Util::image_blurred_bg($imageNameFilename, $imageBlurredNameFilename, $coords)) {
    echo json_encode([
            "ok" => true,
            "new_name" => $imageBlurredName,
            "new_href" => $imageBlurredNameHref
    ]);
} else {
    echo json_encode([
            "ok" => false,
            "error" => "No se pudo blurear"
    ]);
}