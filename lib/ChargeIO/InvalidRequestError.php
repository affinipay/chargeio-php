<?php

class ChargeIO_InvalidRequestError extends ChargeIO_ApiError {
	public function __construct($json) {
		parent::__construct($json);
	}
}
