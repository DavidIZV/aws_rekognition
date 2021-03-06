<?php

class Util {

    static function redirect ($subPath) {

        self::print("Redireccion a: " . $subPath);
        header("Location: /pia/upload/" . $subPath);
        die();
    }

    static function getParam ($name) {

        $value = NULL;
        if (isset($_GET[$name]) && $_GET[$name] != NULL) {
            $value = $_GET[$name];
        }
        if ($value == NULL && isset($_POST[$name]) && $_POST[$name] != NULL) {
            $value = $_POST[$name];
        }
        return $value;
    }

    static function print ($message = "") {

        self::writeLog("Log -------------> " . json_encode($message));
    }

    static function createDirIfNotExists ($target = NULL) {

        if ($target != NULL && (! file_exists($target) && ! is_dir($target))) {
            Util::print("Creado directorio: " . $target);
            mkdir($target, 0777, true);
        }
    }

    private static function writeLog ($linea = '') {

        $logDir = __DIR__ . '/../log/';
        Util::createDirIfNotExists($logDir);

        $logFile = $logDir . self::systemDate() . ".log";
        $file = fopen($logFile, "a+");

        fwrite($file, $linea . PHP_EOL);

        fclose($file);
    }

    public static function systemDate () {

        return (new DateTime())->format('Y-m-d');
    }

    static function image_blurred_bg ($imageFileName, $dest, $coords) {

        try {
            $info = getimagesize($imageFileName);
        } catch (Exception $e) {
            return false;
        }

        $mimetype = image_type_to_mime_type($info[2]);
        switch ($mimetype) {
            case 'image/jpeg':
                $image1 = imagecreatefromjpeg($imageFileName);
                $image2 = imagecreatefromjpeg($imageFileName);
                break;
            case 'image/gif':
                $image1 = imagecreatefromgif($imageFileName);
                $image2 = imagecreatefromgif($imageFileName);
                break;
            case 'image/png':
                $image1 = imagecreatefrompng($imageFileName);
                $image2 = imagecreatefrompng($imageFileName);
                break;
            default:
                return false;
        }

        $wor = imagesx($image1);
        $hor = imagesy($image1);
        
        foreach ($coords as $clave=>$valor){
            $coords[$clave]['top'] = $coords[$clave]['top'] * $hor;
            $coords[$clave]['height'] = $coords[$clave]['height'] * $hor;
            $coords[$clave]['left'] = $coords[$clave]['left'] * $wor;
            $coords[$clave]['width'] = $coords[$clave]['width'] * $wor;
        }

        for ($i = 1; $i<10; $i++) {
             imagefilter($image1, IMG_FILTER_GAUSSIAN_BLUR);
        }
        
        foreach ($coords as $clave=>$valor){
            imagecopy($image2, $image1, $valor['left'], $valor['top'], $valor['left'], $valor['top'], $valor['width'], $valor['height']);
        }
        imagepng($image2, $dest, 0, PNG_NO_FILTER);

        imagedestroy($image1);
        imagedestroy($image2);

        return true;
    }
}