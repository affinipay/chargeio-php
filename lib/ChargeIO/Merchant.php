<?php

class ChargeIO_Merchant extends ChargeIO_Object {
	public function __construct($attributes = array(), $connection = null) {
		parent::__construct($attributes, $connection);
		$this->connection = $connection;
		$this->attributes = array_merge($this->attributes, $attributes);
	}
	
	public static function findCurrent() {
		return self::findCurrentUsingCredentials(ChargeIO::getCredentials());
	}
	
	public static function findCurrentUsingCredentials($credentials) {
		$conn = new ChargeIO_Connection($credentials);
		$response = $conn->get('/merchant');
		return new ChargeIO_Merchant($response, $conn);
	}
	
	public function update($attributes = array()) {
		$merchantAttrs = array_merge($this->attributes, $attributes);
		unset($merchantAttrs['merchant_accounts']);
		unset($merchantAttrs['ach_accounts']);
		$response = $this->connection->put('/merchant', $merchantAttrs);
		$this->updateAttributes($response);
	}
	
	public function merchantAccounts() {
		$accounts = array();
		
		if (array_key_exists('merchant_accounts', $this->attributes)) {
			foreach ($this->attributes['merchant_accounts'] as $accountAttrs) {
				array_push($accounts, new ChargeIO_MerchantAccount($accountAttrs, $this->connection));
			}
		}
		
		return $accounts;
	}
	
	public function achAccounts() {
		error_log('getting ach accounts');
		$accounts = array();
		
		if (array_key_exists('ach_accounts', $this->attributes)) {
			error_log('has accounts key');
			foreach ($this->attributes['ach_accounts'] as $accountAttrs) {
				error_log('processing account attrs: ' . implode(', ', $accountAttrs));
				array_push($accounts, new ChargeIO_AchAccount($accountAttrs, $this->connection));
			}
		}
		
		return $accounts;
	}
}