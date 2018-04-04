<?php

abstract class ChargeIO {
	public static $credentials;
	public static $apiUrl = 'https://api.chargeio.com/v1';
	public static $verifySslCerts = true;
	public static $debug = false;
	const VERSION = '1.0.0';

	public static function getCredentials() {
		return self::$credentials;
	}

	public static function setCredentials($credentials) {
		self::$credentials = $credentials;
	}

	public static function getApiUrl() {
		return self::$apiUrl;
	}
	
	public static function setApiUrl($apiUrl) {
		self::$apiUrl = $apiUrl;
	}
	
	public static function getVerifySslCerts() {
		return self::$verifySslCerts;
	}

	public static function setVerifySslCerts($verify) {
		self::$verifySslCerts = $verify;
	}
	
	public static function setDebug($debug) {
		self::$debug = $debug;
	}
}
