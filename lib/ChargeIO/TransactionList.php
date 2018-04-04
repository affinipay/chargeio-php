<?php

class ChargeIO_TransactionList extends ChargeIO_List {
	protected function parseResult($offset) {
		$transAttrs = $this->attributes['results'][$offset];
		try {
			return ChargeIO_Transaction::parse($transAttrs, $this->connection);
		}
		catch(Exception $ex) {
			throw new ChargeIO_Error('Unexpected transaction type ' . $transAttrs['type'] . ' at offset ' . $offset);
		}
	}
}