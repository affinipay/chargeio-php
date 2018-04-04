<?php
class ChargeIO_TestCase extends PHPUnit_Framework_TestCase
{
	public function newCard($number = '4242424242424242') {
		return new ChargeIO_Card(array(
			'number' => $number,
			'cvv' => '123',
			'exp_month' => 12,
			'exp_year' => 2020,
			'name' => 'PHP Customer',
			'address1' => '123 PHP Way',
			'city' => 'Austin',
			'state' => 'TX',
			'postal_code' => '78730'
		));
	}
}