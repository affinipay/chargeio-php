<?php

class ChargeIO_Charge extends ChargeIO_Transaction {
	public static function create($paymentMethod, $amount, $params = array()) {
		return self::createUsingCredentials(ChargeIO::getCredentials(), $paymentMethod, $amount, $params);
	}
	
	public static function createUsingCredentials($credentials, $paymentMethod, $amount, $params = array()) {
		$conn = new ChargeIO_Connection($credentials);
		
		$params['amount'] = $amount;
		if ($paymentMethod instanceof ChargeIO_PaymentMethodReference) {
			$params['method'] = $paymentMethod->id;
		}
		else {
			$params['method'] = $paymentMethod->attributes;
		}

		$response = $conn->post('/charges', $params);
		return new ChargeIO_Charge($response, $conn);
	}
	
	public static function authorize($paymentMethod, $amount, $params = array()) {
		return self::authorizeUsingCredentials(ChargeIO::getCredentials(), $paymentMethod, $amount, $params);
	}
	
	public static function authorizeUsingCredentials($credentials, $paymentMethod, $amount, $params = array()) {
		return self::createUsingCredentials($credentials, $paymentMethod, $amount, array_merge($params, array('auto_capture' => false)));
	}
	
	public function refund($amount, $params = array()) {
		$params['amount'] = $amount;
		$response = $this->connection->post('/charges/' . $this->id . '/refund', $params);
		return new ChargeIO_Refund($response, $this->connection);
	}

	public function capture($amount, $params = array()) {
		$params['amount'] = $amount;
		$response = $this->connection->post('/charges/' . $this->id . '/capture', $params);
		$this->updateAttributes($response);
	}
	
		public static function allHolds($params = array()) {
		return self::allHoldsUsingCredentials(ChargeIO::getCredentials(), $params);
	}
	
	public static function allHoldsUsingCredentials($credentials, $params = array()) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/charges/holds', $params);
		return new ChargeIO_TransactionList($response, $conn);
	}
}