<?php
require './../autoload.php';

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

$coords = $input["coords"];

$imageName = $input["imageName"];
$imageNameFilename = __DIR__ . '/../originales/' . $imageName;

$imageBlurredName = $imageName . "Copy.jpg";
$imageBlurredNameFilename = $imageNameFilename . "Copy.jpg";

if (Util::image_blurred_bg($imageNameFilename, $imageBlurredNameFilename, $coords)) {
    echo json_encode([
            "ok" => true,
            "new_name" => $imageBlurredName
    ]);
} else {
    echo json_encode([
            "ok" => false,
            "error" => "No se pudo blurear"
    ]);
}