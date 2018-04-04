<?php

class ChargeIO_RecurringCharge extends ChargeIO_Object {
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

		$response = $conn->post('/recurring/charges', $params);
		return new ChargeIO_RecurringCharge($response, $conn);
	}
	
	public static function all($params = array()) {
		return self::allUsingCredentials(ChargeIO::getCredentials(), $params);
	}
	
	public static function allUsingCredentials($credentials, $params = array()) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/recurring/charges', $params);
		return new ChargeIO_RecurringChargeList($response, $conn);
	}
	
	public static function findById($id) {
		return self::findByIdUsingCredentials(ChargeIO::getCredentials(), $id);
	}
	
	public static function findByIdUsingCredentials($credentials, $id) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/recurring/charges/' . $id);
		return new ChargeIO_RecurringCharge($response, $conn);
	}
	
	public function update($params = array()) {
		$response = $this->connection->patch('/recurring/charges/' . $this->id, $params);
		$this->updateAttributes($response);
	}
	
	public function cancel($params = array()) {
		$response = $this->connection->post('/recurring/charges/' . $this->id . '/cancel', $params);
		$this->updateAttributes($response);
	}

	public function delete($params = array()) {
		$response = $this->connection->delete('/recurring/charges/' . $this->id, $params);
		$this->updateAttributes($response);
	}
	
	public function occurrences($params = array()) {
		$response = $this->connection->get('/recurring/charges/' . $this->id . '/occurrences', $params);
		return new ChargeIO_RecurringChargeOccurrenceList($response, $this->connection);
	}
	
	public function findOccurrenceById($occurrence_id) {
		$response = $this->connection->get('/recurring/charges/' . $this->id . '/occurrences/' . $occurrence_id);
		return new ChargeIO_RecurringChargeOccurrence($response, $this->connection);
	}
}