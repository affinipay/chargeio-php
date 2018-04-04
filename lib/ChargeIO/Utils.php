<?php

abstract class ChargeIO_Utils {
	public static function underscore($string) {
		return strtolower(preg_replace('/(?<=\\w)(?=[A-Z0-9])/', "_$1", $string));
	}
}