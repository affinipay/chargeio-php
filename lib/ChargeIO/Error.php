<?php

class ChargeIO_Error extends Exception {
	public function __construct($message=null, $json=null) {
		parent::__construct($message);
		$this->json = $json;
	}

	public function getJson() {
		return $this->json;
	}
}
