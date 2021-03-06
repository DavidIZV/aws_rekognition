<?php

class Uploader {

    static private function isUploadedFile ($name) {

        return isset($_FILES[$name]);
    }

    static private function isValidUploadedFile ($file) {

        $result = true;

        $error = $file['error'];

        $name = $file['name'];

        $size = $file['size'];

        $tmp_name = $file['tmp_name'];

        $type = $file['type'];

        if ($error != 0 || $name == '' || $size == 0 ||
                strpos($type, 'image/') === false ||
                ! is_uploaded_file($tmp_name)) {

            $result = false;
        } else {

            $mcType = mime_content_type($tmp_name);

            if (strpos($mcType, 'image/') === false) {

                $result = false;
            }
        }

        return $result;
    }

    static private function moveFile ($file) {

        $target = __DIR__ . '/../originales';

        Util::createDirIfNotExists($target);
        
        Util::print("Directorio temporal originales");

        $uniqueName = uniqid('image_');

        $name = $file['name'];

        $extension = pathinfo($name, PATHINFO_EXTENSION);

        $tmp_name = $file['tmp_name'];

        $uploadedFile = $target . '/' . $uniqueName . '.' . $extension;

        Util::print("Justo antes de subir");
        if (move_uploaded_file($tmp_name, $uploadedFile)) {

            Util::print("Fichero subido");
            return [
                    $uploadedFile,
                    $uniqueName . '.' . $extension,
                    $uniqueName,
                    $extension
            ];
        }

        return false;
    }

    static function uploadFile ($paramName) {

        if (! self::isUploadedFile($paramName)) {

            Util::print("No esta subido el fichero");
            return false;
        }

        $file = $_FILES[$paramName];

        if (! self::isValidUploadedFile($file)) {

            Util::print("No es un fichero valido");
            return false;
        }

        return self::moveFile($file);
    }
}