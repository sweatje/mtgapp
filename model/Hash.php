<?php

class Hash implements ArrayAccess {
	protected $store = array();
	function __construct($init = array()) {
		foreach($init as $key => $val) $this->set($key, $val);
	}
	function get($key) {
		if (array_key_exists($key,$this->store))
			return $this->store[$key];
	}
	function set($key, $value=null) {
		$this->store[$key] = $value;
	}
	function __set($key, $value=null) {
		$this->set($key, $value);
	}
	function __get($key) {
		return $this->get($key);
	}
	// next four methods for ArrayAccess interface
	function offsetExists($key) {
		return array_key_exists($key,$this->store);
	}
	function offsetGet($key) {
		return $this->get($key);
	}
	function offsetSet($key, $value) {
		$this->set($key, $value);
	}
	function offsetUnset($key) {
		unset($this->store[$key]);
	}
}
