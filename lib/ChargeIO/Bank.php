<?php

class ChargeIO_Bank extends ChargeIO_Object implements ChargeIO_PaymentMethod {
	public function __construct($attributes = array(), $connection = null) {
		parent::__construct($attributes, $connection);
		$this->connection = $connection;
		$this->attributes = array_merge($this->attributes, $attributes);
		$this->attributes['type'] = 'bank';
	}
	
	public static function create($attributes = array()) {
		return self::createUsingCredentials(ChargeIO::getCredentials(), $attributes);
	}
	
	public static function createUsingCredentials($credentials, $attributes = array()) {
		$conn = new ChargeIO_Connection($credentials);

		$attributes['type'] = 'bank';
		$response = $conn->post('/banks', $attributes);
		return new ChargeIO_Bank($response, $conn);
	}
	
	public static function all($params = array()) {
		return self::allUsingCredentials(ChargeIO::getCredentials(), $params);
	}
	
	public static function allUsingCredentials($credentials, $params = array()) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/banks', $params);
		return new ChargeIO_BankList($response, $conn);
	}
	
	public static function findById($id) {
		return self::findByIdUsingCredentials(ChargeIO::getCredentials(), $id);
	}
	
	public static function findByIdUsingCredentials($credentials, $id) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/banks/' . $id);
		return new ChargeIO_Bank($response, $conn);
	}
	
	public function delete() {
		$response = $this->connection->delete('/banks/' . $this->id);
		$this->updateAttributes($response);
	}
}