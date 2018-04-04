<?php
class RecurringChargeTest extends ChargeIO_TestCase
{
	public function testRecurringLifecycle() {
		$m = ChargeIO_Merchant::findCurrent();
		$accounts = $m->merchantAccounts();
		$account = reset($accounts);
		
		// New recurring charge of $25 paid monthly on the 1st
		$card = $this->newCard();
		$rc = ChargeIO_RecurringCharge::create($card, 2500, array(
			'description' => 'Test Recurring Charge',
			'account_id' => $account->id,
			'schedule' => array(
				'start' => '2016-01-01',
				'interval_unit' => 'MONTH',
				'interval_delay' => 1
			)
		));
		$this->assertNotNull($rc);
		$this->assertEquals('ACTIVE', $rc->status);

		
		// Retrieve occurrences -- should just be the first pending occurrence
		$occs = $rc->occurrences();
		$this->assertEquals(1, $occs->getTotalEntries());
		
		$occ = $occs[0];
		$this->assertEquals('PENDING', $occ->status);
		
		
		// Pre-pay the first occurrence
		$occ->pay();
		$this->assertEquals('PAID', $occ->status);
		
		
		// We now have two occurrences. Mark the new pending occurrence ignored.
		$occs = $rc->occurrences();
		$this->assertEquals(2, $occs->getTotalEntries());
		
		$occ = $occs[0];
		$this->assertEquals('PENDING', $occ->status);

		$occ->ignore();
		$this->assertEquals('IGNORED', $occ->status);
		
		
		// Change the payment method associated with the recurring charge
		$card = $this->newCard('4111111111111111');
		$rc->update(array('method' => $card->attributes));
		$this->assertEquals('************1111', $rc->method['number']);
		
		
		// Pay the previously ignored occurrence
		$occ->pay();
		$this->assertEquals('PAID', $occ->status);
		
		
		// Cancel the recurring charge
		$rc = ChargeIO_RecurringCharge::findById($rc->id);
		$rc->cancel();
		$this->assertEquals('COMPLETED', $rc->status);
		$this->assertEquals('user_canceled', $rc->status_reason);
		
		
		// Delete the recurring charge
		$rc->delete();
		$this->assertEquals('DELETED', $rc->status);
	}
	
	public function testRecurringFromCharge() {
		$m = ChargeIO_Merchant::findCurrent();
		$accounts = $m->merchantAccounts();
		$account = reset($accounts);

		// Create a recurring charge from a new charge with a schedule
		$card = $this->newCard();
		$charge = ChargeIO_Charge::create($card, 10000, array(
			'account_id' => $account->id,
			'recur' => array(
				'interval_unit' => 'WEEK',
				'interval_delay' => 1
			)
		));
		$this->assertNotNull($charge);
		$this->assertEquals('AUTHORIZED', $charge->status);
		$this->assertNotNull($charge->recurring_charge_id);
		$this->assertNotNull($charge->recurring_charge_occurrence_id);
		
		// Retrieve occurrences -- should contain the initial paid occurrence associated with the charge and the next pending occurrence
		$rc = ChargeIO_RecurringCharge::findById($charge->recurring_charge_id);
		$this->assertNotNull($rc);
		$occs = $rc->occurrences();
		$this->assertEquals(2, $occs->getTotalEntries());
		
		$this->assertEquals('PENDING', $occs[0]->status);
		$this->assertEquals('PAID', $occs[1]->status);
		$this->assertEquals(1, count($occs[1]->transactions));
		$this->assertEquals($charge->id, $occs[1]->transactions[0]['id']);

		$transaction_id = $occs[1]->transactions[sizeof($occs[1]->transactions) - 1]['id'];
		$this->assertNotNull($transaction_id);

		$c = new ChargeIO_Charge($occs[1]->transactions[0], new ChargeIO_Connection(ChargeIO::getCredentials()));
		$this->assertNotNull($c);
		$this->assertEquals($charge->id, $c->id);
		$r = $c->refund(10);
		$this->assertNotNull($r);
		$this->assertNotNull($r->id);

	}
}
