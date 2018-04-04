<?php

class ChargeIO_AchAccount extends ChargeIO_Object {
	public function __construct($attributes = array(), $connection = null) {
		parent::__construct($attributes, $connection);
		$this->connection = $connection;
		$this->attributes = array_merge($this->attributes, $attributes);
	}
	
	public function update($attributes = array()) {
		$accountAttrs = array_merge($this->attributes, $attributes);
		$response = $this->connection->put('/ach-accounts/' . $this->id, $accountAttrs);
		$this->updateAttributes($response);
	}
}