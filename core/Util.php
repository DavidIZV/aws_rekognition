<?php

class Util {

	static function redirect($url) {

		header ( "Location: /pia/upload/" . $url );
		die ();
	}

	static function print($message = "") {

		self::writeLog ( "Log -------------> " . json_encode ( $message ) );
	}

	static function createDirIfNotExists($target = NULL) {

		if ($target != NULL && ! file_exists ( $target )) {
			mkdir ( $target, 0777, true );
		}
	}

	private static function writeLog($linea = '') {

		$logDir = __DIR__ . './../log/';
		Util::createDirIfNotExists ( $logDir );

		$logFile = $logDir . self::systemDate () . ".log";
		$file = fopen ( $logFile, "a+" );

		fwrite ( $file, $linea . PHP_EOL );

		fclose ( $file );
	}

	public static function systemDate() {

		return (new DateTime ())->format ( 'Y-m-d' );
	}

}