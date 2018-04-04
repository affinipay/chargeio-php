<?php

class ChargeIO_MerchantAccount extends ChargeIO_Object {
	public function __construct($attributes = array(), $connection = null) {
		parent::__construct($attributes, $connection);
		$this->connection = $connection;
		$this->attributes = array_merge($this->attributes, $attributes);
	}
	
	public function update($attributes = array()) {
		$accountAttrs = array_merge($this->attributes, $attributes);
		$response = $this->connection->put('/accounts/' . $this->id, $accountAttrs);
		$this->updateAttributes($response);
	}
}