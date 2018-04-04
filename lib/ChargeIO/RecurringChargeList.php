<?php

class ChargeIO_RecurringChargeList extends ChargeIO_List {
	protected function parseResult($offset) {
		$attrs = $this->attributes['results'][$offset];
		return new ChargeIO_RecurringCharge($attrs, $this->connection);
	}
}