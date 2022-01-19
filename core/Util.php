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

    static function image_blurred_bg ($image, $dest, $coords) {

        try {
            $info = getimagesize($image);
        } catch (Exception $e) {
            return false;
        }

        $mimetype = image_type_to_mime_type($info[2]);
        switch ($mimetype) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($image);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($image);
                break;
            case 'image/png':
                $image = imagecreatefrompng($image);
                break;
            default:
                return false;
        }

        $wor = imagesx($image);
        $hor = imagesy($image);
        $back = imagecreatetruecolor($coords[0]['width'], $coords[0]['height']);

        $maxfact = max($coords[0]['width'] / $wor, $coords[0]['height'] / $hor);
        $new_w = $wor * $maxfact;
        $new_h = $hor * $maxfact;
        imagecopyresampled($back, $image, - (($new_w - $coords[0]['width']) / 2), - (($new_h - $coords[0]['height']) / 2), 0, 0, $new_w, $new_h,
                $wor, $hor);

        // Blur Image
        for ($x = 1; $x <= 40; $x ++) {
            imagefilter($back, IMG_FILTER_GAUSSIAN_BLUR, 999);
        }
        imagefilter($back, IMG_FILTER_SMOOTH, 99);
        imagefilter($back, IMG_FILTER_BRIGHTNESS, 10);

        $minfact = min($coords[0]['width'] / $wor, $coords[0]['height'] / $hor);
        $new_w = $wor * $minfact;
        $new_h = $hor * $minfact;

        $front = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($front, $image, 0, 0, 0, 0, $new_w, $new_h, $wor, $hor);

        imagecopymerge($back, $front, - (($new_w - $coords[0]['width']) / 2), - (($new_h - $coords[0]['height']) / 2), 0, 0, $new_w, $new_h, 100);

        // output new file
        imagejpeg($back, $dest, 90);
        imagedestroy($back);
        imagedestroy($front);

        return true;
    }
}