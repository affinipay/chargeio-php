<?php

class ChargeIO_Signature extends ChargeIO_Object {
	public static function findById($id) {
		return self::findByIdUsingCredentials(ChargeIO::getCredentials(), $id);
	}
	
	public static function findByIdUsingCredentials($credentials, $id) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/signatures/' . $id);
		return new ChargeIO_Signature($response, $conn);
	}
}