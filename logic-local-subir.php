<?php
require './autoload.php';

$uploaded = Uploader::uploadFile('image');

if ($uploaded) {
    Util::redirect("view-bucket-vista.php");
} else {
    Util::redirect("index.php?error" . urlencode("Error en la subida de la imagen"));
}