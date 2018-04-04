<?php

abstract class ChargeIO_List extends ChargeIO_Object implements ArrayAccess {
	public function __construct($attributes = array(), $connection = null) {
		parent::__construct($attributes, $connection);

		$this->page = $attributes['page']; # 1-based
		$this->pageSize = $attributes['page_size'];
		$this->pageEntries = sizeof($attributes['results']);
		$this->totalEntries = $attributes['total_entries'];
		$this->totalPages = $this->pageSize == 0 ? 0 : ceil($this->totalEntries / $this->pageSize);
	}
	
	public function getPage() {
		return $this->page;
	}
	
	public function hasNextPage() {
		return $this->page < $this->totalPages;
	}
	
	public function getPageSize() {
		return $this->pageSize;
	}
	
	public function getPageEntries() {
		return $this->pageEntries;
	}
	
	public function getTotalEntries() {
		return $this->totalEntries;
	}
	
	public function getTotalPages() {
		return $this->totalPages;
	}
	
	public function offsetExists($offset) {
		return $this->attributes['results'] && array_key_exists($offset, $this->attributes['results']);
	}
	
	public function offsetGet($offset) {
		return $this->parseResult($offset);
	}
	
	public function offsetSet($offset, $value) {
		# Unsupported
	}
	
	public function offsetUnset($offset) {
		# Unsupported
	}
	
	abstract protected function parseResult($offset);
}