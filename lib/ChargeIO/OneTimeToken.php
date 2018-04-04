<?php

class ChargeIO_OneTimeToken extends ChargeIO_Object implements ChargeIO_PaymentMethod {
	public function __construct($attributes = array(), $connection = null) {
		parent::__construct($attributes, $connection);
		$this->connection = $connection;
		$this->attributes = array_merge($this->attributes, $attributes);
		$this->attributes['type'] = 'card';
	}
	
	public static function createOneTimeCard($attributes = array()) {
		return self::createOneTimeCardUsingCredentials(ChargeIO::getCredentials(), $attributes);
	}
	
	public static function createOneTimeCardUsingCredentials($credentials, $attributes = array()) {
		$conn = new ChargeIO_Connection($credentials);

		$attributes['type'] = 'card';
		$response = $conn->post('/tokens', $attributes);
		return new ChargeIO_OneTimeToken($response, $conn);
	}
	
	public static function createOneTimeBank($attributes = array()) {
		return self::createOneTimeBankUsingCredentials(ChargeIO::getCredentials(), $attributes);
	}
	
	public static function createOneTimeBankUsingCredentials($credentials, $attributes = array()) {
		$conn = new ChargeIO_Connection($credentials);

		$attributes['type'] = 'bank';
		$response = $conn->post('/tokens', $attributes);
		return new ChargeIO_OneTimeToken($response, $conn);
	}
}