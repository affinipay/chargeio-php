<?php

abstract class ChargeIO_Object {
	protected $connection;
	public $attributes = array();

	public function __construct($attributes = array(), $connection = null) {
		$this->connection = $connection;
		$this->attributes = array_merge($this->attributes, $attributes);
	}

	public function __get($prop) {
		$underscoredProp = ChargeIO_Utils::underscore($prop);
		if (isset($this->attributes[$underscoredProp])) {
			return $this->attributes[$underscoredProp];
		} else {
			return null;
		}
	}

	public function __set($prop, $value) {
		$underscoredProp = ChargeIO_Utils::underscore($prop);
		$this->attributes[$underscoredProp] = $value;
	}

	public function updateAttributes($attributes = array()) {
		foreach ($attributes as $key => $value) {
			if ($value !== '' || !isset($this->attributes[$key])) {
				$this->attributes[$key] = $value;
			}
		}
	}
}