<?php

class ChargeIO_BankList extends ChargeIO_List {
	protected function parseResult($offset) {
		$bankAttrs = $this->attributes['results'][$offset];
		return new ChargeIO_Bank($bankAttrs, $this->connection);
	}
}