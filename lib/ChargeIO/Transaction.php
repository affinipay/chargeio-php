<?php

class ChargeIO_Transaction extends ChargeIO_Object {
	public static function all($params = array()) {
		return self::allUsingCredentials(ChargeIO::getCredentials(), $params);
	}
	
	public static function allUsingCredentials($credentials, $params = array()) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/transactions', $params);
		return new ChargeIO_TransactionList($response, $conn);
	}
		
	public static function findById($id) {
		return self::findByIdUsingCredentials(ChargeIO::getCredentials(), $id);
	}
	
	public static function findByIdUsingCredentials($credentials, $id) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/transactions/' . $id);
		return self::parse($response, $conn);
	}
	
	public static function parse($attributes, $connection = null) {
		switch($attributes['type']) {
			case 'CHARGE':
				return new ChargeIO_Charge($attributes, $connection);
			case 'REFUND':
				return new ChargeIO_Refund($attributes, $connection);
			case 'CREDIT':
				return new ChargeIO_Credit($attributes, $connection);
		}
		
		throw new ChargeIO_Error('Unexpected transaction type ' . $attributes['type']);
	}
	
	public function void($params = array()) {
		$response = $this->connection->post('/transactions/' . $this->id . '/void', $params);
		$this->updateAttributes($response);
	}
	
	public function sign($signature, $gratuity = NULL, $mime_type = 'chargeio/jsignature', $params = array()) {
		$params['data'] = $signature;
		$params['mime_type'] = $mime_type;
		if ($gratuity) {
			$params['gratuity'] = $gratuity;
		}
		$response = $this->connection->post('/transactions/' . $this->id . '/sign', $params);
		$this->updateAttributes($response);
	}
}