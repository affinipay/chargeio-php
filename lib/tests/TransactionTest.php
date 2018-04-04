<?php
class TransactionTest extends ChargeIO_TestCase
{
	public function testTransactionLifecycle() {
		// New charge for $10
		$card = $this->newCard();
		$charge = ChargeIO_Charge::create($card, 1000);
		$this->assertNotNull($charge);
		$this->assertEquals('AUTHORIZED', $charge->status);
		
		
		// Attach signature and add gratuity of $2.50
		$sigdata = '[{"x":[179,179,179,179,180,188,195,206,218,228,245,252,261,267,270,270,269,262,254,246,237,230,225,221,219,219,222,229,239,251,263,274,282,286,288,289,286],"y":[77,84,89,97,104,113,120,127,132,133,133,133,128,121,114,106,99,93,87,85,85,85,87,93,99,109,120,129,138,144,146,147,145,141,134,130,127]}]';
		$charge->sign($sigdata, 250);
		$this->assertNotNull($charge->signature_id);
		$this->assertEquals(250, $charge->gratuity);
		$sig = ChargeIO_Signature::findById($charge->signature_id);
		$this->assertNotNull($sig);
		$this->assertEquals($sigdata, $sig->data);
		$this->assertEquals('chargeio/jsignature', $sig->mime_type);
		
		
		// Refund $1.75
		$refund = $charge->refund(175);
		$this->assertNotNull($refund);
		$this->assertEquals('AUTHORIZED', $refund->status);
		$this->assertEquals(175, $refund->amount);
		
		
		// Void the refund
		$refund->void();
		$this->assertEquals('VOIDED', $refund->status);

		
		// Capture the original charge, but for only $9.10
		$charge->capture(910);
		$this->assertEquals('COMPLETED', $charge->status);
		$this->assertEquals(910, $charge->amount);
	}
	
	public function testHolds() {
		// New authorization for $20 from one-time card token
		$token = ChargeIO_OneTimeToken::createOneTimeCard($this->newCard()->attributes);
		$charge = ChargeIO_Charge::authorize(new ChargeIO_PaymentMethodReference(array('id' => $token->id)), 2000);
		$this->assertNotNull($charge);
		$this->assertEquals('AUTHORIZED', $charge->status);
		$this->assertEquals(false, $charge->auto_capture);
		
		
		// Retrieve first page (most recent) pending holds
		$holds = ChargeIO_Charge::allHolds();
		$this->assertGreaterThanOrEqual(1, $holds->getTotalEntries());
		$this->assertEquals($charge->id, $holds[0]->id);
		
		
		// Convert manual-capture authorization to auto-capture
		$charge->capture(2000, array('capture_time' => 'NEXT_AUTO_CAPTURE'));
		$this->assertEquals('AUTHORIZED', $charge->status);
		$this->assertEquals(true, $charge->auto_capture);
		
		
		// Charge no longer appears in pending holds
		$holds = ChargeIO_Charge::allHolds();
		$this->assertGreaterThanOrEqual(0, $holds->getTotalEntries());
		if ($holds->getTotalEntries() > 0) {
			$this->assertNotEquals($charge->id, $holds[0]->id);
		}
	}
}