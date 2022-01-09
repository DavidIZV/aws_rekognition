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

        if ($target != NULL && ! file_exists($target)) {
            mkdir($target, 0777, true);
        }
    }

    private static function writeLog ($linea = '') {

        $logDir = './log/';
        Util::createDirIfNotExists($logDir);

        $logFile = $logDir . self::systemDate() . ".log";
        $file = fopen($logFile, "a+");

        fwrite($file, $linea . PHP_EOL);

        fclose($file);
    }

    public static function systemDate () {

        return (new DateTime())->format('Y-m-d');
    }
}