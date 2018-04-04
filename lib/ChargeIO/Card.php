<?php

class ChargeIO_Card extends ChargeIO_Object implements ChargeIO_PaymentMethod {
	public function __construct($attributes = array(), $connection = null) {
		parent::__construct($attributes, $connection);
		$this->connection = $connection;
		$this->attributes = array_merge($this->attributes, $attributes);
		$this->attributes['type'] = 'card';
	}
	
	public static function create($attributes = array()) {
		return self::createUsingCredentials(ChargeIO::getCredentials(), $attributes);
	}
	
	public static function createUsingCredentials($credentials, $attributes = array()) {
		$conn = new ChargeIO_Connection($credentials);

		$attributes['type'] = 'card';
		$response = $conn->post('/cards', $attributes);
		return new ChargeIO_Card($response, $conn);
	}
	
	public static function all($params = array()) {
		return self::allUsingCredentials(ChargeIO::getCredentials(), $params);
	}
	
	public static function allUsingCredentials($credentials, $params = array()) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/cards', $params);
		return new ChargeIO_CardList($response, $conn);
	}
	
	public static function findById($id) {
		return self::findByIdUsingCredentials(ChargeIO::getCredentials(), $id);
	}
	
	public static function findByIdUsingCredentials($credentials, $id) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/cards/' . $id);
		return new ChargeIO_Card($response, $conn);
	}
	
	public function delete() {
		$response = $this->connection->delete('/cards/' . $this->id);
		$this->updateAttributes($response);
	}
}