<?php

class ChargeIO_PaymentMethodReference extends ChargeIO_Object implements ChargeIO_PaymentMethod {
	public function __get($prop) {
		switch($prop) {
			default:
				$underscoredProp = ChargeIO_Utils::underscore($prop);
				if (isset($this->attributes[$underscoredProp])) {
					return $this->attributes[$underscoredProp];
				} else {
					return null;
				}
		}
	}
}