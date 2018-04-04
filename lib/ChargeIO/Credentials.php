<?php

class ChargeIO_Credentials {
	private $public_key;
	private $secret_key;

	public function __construct($public_key, $secret_key) {
		$this->public_key = $public_key;
		$this->secret_key = $secret_key;
	}

	public function getPublicKey() {
		return $this->public_key;
	}
	
	public function getSecretKey() {
		return $this->secret_key;
	}
}