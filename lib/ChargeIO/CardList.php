<?php

class ChargeIO_CardList extends ChargeIO_List {
	protected function parseResult($offset) {
		$cardAttrs = $this->attributes['results'][$offset];
		return new ChargeIO_Card($cardAttrs, $this->connection);
	}
}