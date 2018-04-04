<?php

class ChargeIO_RecurringChargeOccurrenceList extends ChargeIO_List {
	protected function parseResult($offset) {
		$attrs = $this->attributes['results'][$offset];
		return new ChargeIO_RecurringChargeOccurrence($attrs, $this->connection);
	}
}