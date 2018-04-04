<?php
class MerchantTest extends ChargeIO_TestCase
{
	public function testCurrentMerchant() {
		$m = ChargeIO_Merchant::findCurrent();
		$this->assertNotNull($m);
	}
}