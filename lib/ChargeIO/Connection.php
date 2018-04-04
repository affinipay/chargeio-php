<?php

class ChargeIO_Connection {
	private $credentials;
	
	public function __construct($credentials) {
		$this->credentials = $credentials;
	}
	
	public function get($path, $params=array()) {
		return $this->request('GET', $path, $params);
	}

	public function post($path, $params) {
		return $this->request('POST', $path, $params);
	}

	public function put($path, $params) {
		return $this->request('PUT', $path, $params);
	}

	public function patch($path, $params) {
		return $this->request('PATCH', $path, $params);
	}
	
	public function delete($path) {
		return $this->request('DELETE', $path, null);
	}

	private function request($method, $path, $params) {
		$headers = array();
		$headers[] = 'Accept: application/json';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, ChargeIO::$apiUrl . $path);
		curl_setopt($ch, CURLOPT_USERAGENT, "ChargeIO PHP Client v" . ChargeIO::VERSION);
		curl_setopt($ch, CURLOPT_USERPWD, $this->credentials->getSecretKey() . ':');
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		#curl_setopt($ch, CURLOPT_CAINFO, realpath(dirname(__FILE__)."/cacert.pem"));
		
		if (!ChargeIO::$verifySslCerts) {
			$opts[CURLOPT_SSL_VERIFYPEER] = false;
		}
		
		$jsonRequest = null;
		switch($method) {
			case 'GET':
				if (!empty($params)) {
					curl_setopt($ch, CURLOPT_URL, ChargeIO::$apiUrl . $path . '?' . http_build_query($params));
				}
				break;
			case 'POST':
				$jsonRequest = json_encode($params, JSON_FORCE_OBJECT);
				$headers[] = 'Content-Length: ' . strlen($jsonRequest);
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequest);
				break;
			case 'PUT':
				$jsonRequest = json_encode($params);
				$headers[] = 'Content-Length: ' . strlen($jsonRequest);
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequest);
				break;
			case 'PATCH':
				$jsonRequest = json_encode($params);
				$headers[] = 'Content-Length: ' . strlen($jsonRequest);
				$headers[] = 'Content-Type: application/json';
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequest);
				break;
			case 'DELETE':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				break;
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		if (ChargeIO::$debug) {
			error_log("\n--------- Request ----------\n");
			error_log("-- " . $method . ": " . ChargeIO::$apiUrl . $path . "\n");
			error_log("-- jsonRequest: " . $jsonRequest . "\n");
			error_log("\n-----------------------------\n");
		}

		$res = curl_exec($ch);
		list($header, $body) = explode("\r\n\r\n", $res, 2);

		if (ChargeIO::$debug) {
			error_log("\n--------- Response ----------\n");
			error_log($header);
			error_log("\n");
			error_log($body);
			error_log("\n-----------------------------\n");
		}

		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return $this->processResponse($statusCode, $body);
	}
	
	protected function processResponse($statusCode, $json) {
		if ($statusCode != 200) {
			$this->handleError($statusCode, $json);
		}
		
		$attributes = array();
		if (strlen($json) > 0) {
			$attributes = json_decode($json, true);
		}
		
		return $attributes;
	}
	
	protected function handleError($statusCode, $json) {
		switch($statusCode) {
			case '400':
			case '404':
			case '422':
				throw new ChargeIO_InvalidRequestError($json);
				break;
			case '401':
				throw new ChargeIO_AuthenticationError();
				break;
		}
		
		throw new ChargeIO_ApiError($json);
	}
}
