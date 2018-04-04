<?php

class ChargeIO_Credit extends ChargeIO_Transaction {
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

		$response = $conn->post('/credits', $params);
		return new ChargeIO_Credit($response, $conn);
	}
}